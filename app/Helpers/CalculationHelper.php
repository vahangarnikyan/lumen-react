<?php

namespace App\Helpers;

use Carbon\Carbon;

use App\BetType;
use App\Vote;
use App\Leaderboard;
use App\Game;
use App\User;
use App\Team;
use App\ManualPollPage;
use App\ManualFuture;
use App\ManualFutureAnswer;
use App\ManualFutureVote;
use App\ManualEvent;
use App\ManualEventBetType;
use App\ManualEventVote;
use GuzzleHttp\Client;
use Log;

class CalculationHelper
{
    public function startGames()
    {
        //
        $date = (new Carbon())->format('Y-m-d H:i:s');
        Game::where('status', Game::$STATUS_NOT_STARTED)
            ->where('start_at', '<=', $date)
            ->update(['status' => Game::$STATUS_STARTED]);
    }

    public function updateGames($period_type = 'day')
    {
        //
        if ($period_type == 'day') {
            $date_start = (new Carbon())->subHours(12)->format('Y-m-d H:i:s');
            $date_end = (new Carbon())->addHours(12)->format('Y-m-d H:i:s');
            Log::info('updateGames every 2 minute');
        }
        else {
            $date_start = (new Carbon())->subDays(5)->format('Y-m-d H:i:s');
            $date_end = (new Carbon())->addDays(25)->format('Y-m-d H:i:s');
            Log::info('updateGames - hourly updated');
        }
        
        $client = new Client([
            'base_uri' => 'http://www.conectate.com.do/deportes/api/',
            'proxy' => getenv('HTTP_PROXY'),
        ]);

        $response = $client->request('GET', 'games', [
            'query' => [
                'date_start' => $date_start,
                'date_end' => $date_end
            ],
        ]);

        $gamesData = json_decode($response->getBody());
        foreach ($gamesData as $gameData) {
            $game_live_info = null;
            if (!$gameData->sport_game_live_info) {
                Log::info('insufficient info game_id: '.$gameData->sport_game_id);
                continue;
            }

            $between_players = 0;
            if (property_exists($gameData->sport_game_live_info, 'awayteam')
            && property_exists($gameData->sport_game_live_info, 'hometeam')) {
                $game_live_info = $gameData->sport_game_live_info;
            }
            else if (property_exists($gameData->sport_game_live_info, 'playerone')
            && property_exists($gameData->sport_game_live_info, 'playertwo')) {
                $game_live_info = $gameData->sport_game_live_info;
                $game_live_info->hometeam = $game_live_info->playerone;
                $game_live_info->awayteam = $game_live_info->playertwo;
                unset($game_live_info->playerone);
                unset($game_live_info->playertwo);
            }
            if (!$game_live_info) {
                continue;
            }

            $game = null;
            $game_general_info = null;
            if ($gameData->sport_game_general_info) {
                if (property_exists($gameData->sport_game_general_info, 'awayteam')
                && property_exists($gameData->sport_game_general_info, 'hometeam')) {
                    $game_general_info = $gameData->sport_game_general_info;
                }
                else if (property_exists($gameData->sport_game_general_info, 'playerone')
                && property_exists($gameData->sport_game_general_info, 'playertwo')) {
                    $game_general_info = $gameData->sport_game_general_info;
                    $game_general_info->hometeam = $game_general_info->playerone;
                    $game_general_info->awayteam = $game_general_info->playertwo;
                    unset($game_general_info->playerone);
                    unset($game_general_info->playertwo);
                }
                if ($game_general_info) {
                    $home_team = Team::where([
                        'ref_id' => $game_general_info->hometeam->local_id,
                        'is_player' => $between_players
                    ])->first();
                    $away_team = Team::where([
                        'ref_id' => $game_general_info->awayteam->local_id,
                        'is_player' => $between_players
                    ])->first();
                    if ($home_team && $away_team) {
                        $game = Game::withTrashed()->where([
                            // 'ref_id' => $item['ref_id'],
                            'league_id' => $gameData->sport_league_id,
                            'home_team_id' => $home_team->id,
                            'away_team_id' => $away_team->id,
                            'start_at' => $gameData->sport_game_date_time,
                        ])->first();
                    }
                }

            }
            if (!$game) {
                $game = Game::where('ref_id', $gameData->sport_game_id)->first();
            }

            if ($game && $game->setting_manually) {
                continue;
            }
            
            if ($game && empty($game->calculating_at) && empty($game->calculated_at)) {
                $status = Game::$STATUS_NOT_STARTED;
                switch ($gameData->sport_game_status) {
                    case Game::$IMPORT_STATUS_SUSPENDED:
                    case Game::$IMPORT_STATUS_CANCELLED:
                        $status = Game::$STATUS_POSTPONED;
                        break;
                    case Game::$IMPORT_SATUS_NOT_STARTED:
                        $status = Game::$STATUS_NOT_STARTED;
                        break;
                    case Game::$IMPORT_STATUS_STARTED:
                        $status = Game::$STATUS_STARTED;
                        break;
                    case Game::$IMPORT_STATUS_ENDED:
                        $status = Game::$STATUS_ENDED;
                        break;
                    default:
                        break;
                }

                $data = [
                    'ref_id' => $gameData->sport_game_id,
                    'home_team_score' => $game_live_info->hometeam->totalscore,
                    'away_team_score' => $game_live_info->awayteam->totalscore,
                    'game_info' => $game_live_info,
                    'status' => $status,
                    'start_at' => $gameData->sport_game_date_time,
                ];
                if ($gameData->sport_game_status === Game::$IMPORT_SATUS_NOT_STARTED
                    || $gameData->sport_game_status === Game::$IMPORT_STATUS_CANCELLED
                    || $gameData->sport_game_status === Game::$IMPORT_STATUS_SUSPENDED
                ) {
                    if ($game_general_info) {
                        $data['game_general_info'] = $game_general_info;
                    }
                }
                $game->update($data);
            }
        }
    }

    public function calculateGames($game_id = false)
    {
        if ($game_id) {
            $game = Game::find($game_id);
            if (!$game->calculated_at && !$game->calculating_at && $game->status === Game::$STATUS_ENDED) {
                $this->calculateGameScore($game);
            }
        } else {
            while (($game = Game::where('status', Game::$STATUS_ENDED)
                ->whereNull('calculated_at')
                ->whereNull('calculating_at')->first())) {
                $this->calculateGameScore($game);
            }
        }
    }

    public function calculateGameScore($game)
    {
        $game->calculating_at = (new Carbon())->format('Y-m-d H:i:s');
        $game->save();
        $votes = Vote::where(['game_id' => $game->id, 'calculated' => false])->get();
        foreach ($votes as $vote) {
            $this->calculateGameVoteScore($game, $vote);
        }
        $game->calculated = true;
        $game->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
        $game->calculated = true;
        $game->save();
    }

    public function calculateGameVoteScore($game, $vote)
    {
        $match_case = $vote->game_bet_type->match_case;
        
        if (!$match_case) {
            $vote->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
            $vote->calculated = true;
            $vote->matched = null;
            $vote->score = 0;
            $vote->save();
            return;
        }

        if ($match_case === Vote::$VOTE_CASE_TIE) {
            if ($match_case === $vote->vote_case) {
                $vote->matched = true;
                $vote->score = $vote->bet_type->tie_win_score;
            } else {
                $vote->matched= false;
                $vote->score = $vote->bet_type->tie_loss_score;
            }
        } else {
            if ($match_case === $vote->vote_case) {
                $vote->matched = true;
                $vote->score = $vote->bet_type->win_score;
            } else {
                $vote->matched= false;
                $vote->score = $vote->bet_type->loss_score;
            }
        }
        $vote->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
        $vote->calculated = true;
        $vote->save();
        
        if ($vote->user->role !== User::$ROLE_UNKNOWN) {
            Leaderboard::addScore($game, $vote);
        }
    }

    public function cancelGameCalculation($game) {
        $game->calculating_at = null;
        $game->calculated_at = null;
        $game->calculated = false;
        $game->save();
        $votes = Vote::where('game_id', $game->id)->where('calculated_at', '!=', null)->get();
        foreach ($votes as $vote) {
            $this->cancelGameVoteScoreCalculation($game, $vote);
        }
    }

    public function cancelGameVoteScoreCalculation($game, $vote) {
        if ($vote->user->role !== User::$ROLE_UNKNOWN) {
            Leaderboard::subtractScore($game, $vote);
        }
        $vote->matched = null;
        $vote->calculated_at = null;
        $vote->score = 0;
        $vote->calculated = false;
        $vote->save();
    }

    public function calculatePollPageScore($poll_page) {
        $poll_page->calculating_at = (new Carbon())->format('Y-m-d H:i:s');
        $poll_page->save();
        if ($poll_page->is_future == 1) {
            $votes = ManualFutureVote::where(['page_id' => $poll_page->id, 'calculated' => false])->get();
            foreach ($votes as $vote) {
                $this->calculateFutureVoteScore($vote);
            }
        } else {
            $votes = ManualEventVote::where(['page_id' => $poll_page->id, 'calculated' => false])->get();
            foreach ($votes as $vote) {
                $this->calculateEventVoteScore($vote);
            }
        }
        $poll_page->calculated = true;
        $poll_page->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
        $poll_page->save();
    }

    public function calculateEventVoteScore($vote) {
        $match_case = $vote->event_bet_type->match_case;
        
        if (!$match_case) {
            $vote->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
            $vote->calculated = true;
            $vote->matched = null;
            $vote->score = 0;
            $vote->save();
            $event = $vote->event;
            $event->calculated = true;
            $event->calculated_at = $vote->calculated_at;
            $event->calculating_at = $vote->calculating_at;
            $event->save();
            return;
        }

        if ($match_case === Vote::$VOTE_CASE_TIE) {
            if ($match_case === $vote->vote_case) {
                $vote->matched = true;
                $vote->score = $vote->event->moneyline_tie_win_points;
            } else {
                $vote->matched= false;
                $vote->score = $vote->event->moneyline_tie_loss_points;
            }
        } else {
            if ($match_case === $vote->vote_case) {
                $vote->matched = true;
                switch ($vote->bet_type->value) {
                    case BetType::$VALUE_SPREAD:
                        $vote->score = $vote->event->spread_win_points;
                        break;
                    case BetType::$VALUE_OVER_UNDER:
                        $vote->score = $vote->event->spread_win_points;
                        break;
                    case BetType::$VALUE_MONEYLINE:
                        if ($vote->vote_case == 'win') {
                            $vote->score = $vote->event->moneyline1_win_points;
                        }
                        else {
                            $vote->score = $vote->event->moneyline2_win_points;
                        }
                        break;
                }
            } else {
                $vote->matched = false;
                switch ($vote->bet_type->value) {
                    case BetType::$VALUE_SPREAD:
                        $vote->score = $vote->event->spread_loss_points;
                        break;
                    case BetType::$VALUE_OVER_UNDER:
                        $vote->score = $vote->event->spread_loss_points;
                        break;
                    case BetType::$VALUE_MONEYLINE:
                        if ($vote->vote_case == 'win') {
                            $vote->score = $vote->event->moneyline1_loss_points;
                        }
                        else {
                            $vote->score = $vote->event->moneyline2_loss_points;
                        }
                        break;
                }
            }
        }
        $vote->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
        $vote->calculated = true;
        $vote->save();

        $event = $vote->event;
        $event->calculated = true;
        $event->calculated_at = $vote->calculated_at;
        $event->calculating_at = $vote->calculating_at;
        $event->save();
        
        if ($vote->user->role !== User::$ROLE_UNKNOWN) {
            Leaderboard::addManualScore($vote);
        }
    }

    public function calculateFutureVoteScore($vote) {
        $matched = null;
        $answer = $vote->answer;
        if (!$answer->is_absent) {
            if ($answer->score > 0) {
                $matched = true;
            }
            else {
                $matched = false;
            }
        }
        
        if ($matched === null) {
            $vote->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
            $vote->calculated = true;
            $vote->matched = null;
            $vote->score = 0;
            $vote->save();
            $future = $vote->future;
            $future->calculated = true;
            $future->calculated_at = $vote->calculated_at;
            $future->calculating_at = $vote->calculating_at;
            $future->save();
            return;
        }

        $vote->matched = $matched;
        if ($matched) {
            $vote->score = $answer->winning_score;
        }
        else {
            $vote->score = $answer->losing_score;
        }

        $vote->calculated_at = (new Carbon())->format('Y-m-d H:i:s');
        $vote->calculated = true;
        $vote->save();

        $future = $vote->future;
        $future->calculated = true;
        $future->calculated_at = $vote->calculated_at;
        $future->calculating_at = $vote->calculating_at;
        $future->save();
        
        if ($vote->user->role !== User::$ROLE_UNKNOWN) {
            Leaderboard::addManualScore($vote);
        }
    }

    public function cancelPollPageCalculation($poll_page) {
        $poll_page->calculating_at = null;
        $poll_page->calculated_at = null;
        $poll_page->calculated = false;
        $poll_page->save();
        if ($poll_page->is_future == 1) {
            $futures = ManualFuture::where(['page_id' => $poll_page->id, 'calculated' => true])->get();
            foreach ($futures as $future) {
                $this->cancelFutureCalculation($future);
            }
        } else {
            $events = ManualEvent::where(['page_id' => $poll_page->id, 'calculated' => true])->get();
            foreach ($events as $event) {
                $this->cancelEventCalculation($event);
            }
        }
    }

    public function cancelFutureCalculation($future) {
        $votes = ManualFutureVote::where(['future_id' => $future->id, 'calculated' => true])->get();
        foreach ($votes as $vote) {
            $this->cancelVoteScoreCalculation($vote);
        }
        $future->calculated = false;
        $future->calculating_at = null;
        $future->calculated_at = null;
        $future->save();
    }

    public function cancelEventCalculation($event) {
        $votes = ManualEventVote::where(['event_id' => $event->id, 'calculated' => true])->get();
        foreach ($votes as $vote) {
            $this->cancelVoteScoreCalculation($vote);
        }
        $event->calculated = false;
        $event->calculating_at = null;
        $event->calculated_at = null;
        $event->save();
    }

    public function cancelVoteScoreCalculation($vote) {
        if ($vote->user->role !== User::$ROLE_UNKNOWN) {
            Leaderboard::subtractManualScore($vote);
        }
        $vote->matched = null;
        $vote->calculated_at = null;
        $vote->score = 0;
        $vote->calculated = false;
        $vote->save();
    }
}
