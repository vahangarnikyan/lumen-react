<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Carbon\Carbon;
use App\Facades\Utils;
use DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    public static $ROLE_UNKNOWN = 'unknown';
    public static $ROLE_ADMIN = 'admin';
    public static $ROLE_VOTER = 'voter';

    use Authenticatable, Authorizable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname',
        'username', 'email', 'password',
        'country',
        'role', 'confirmed',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getConfirmationUrlAttribute()
    {
        $key = env('APP_KEY');
        $token = [
            'exp' => time() + 60 * 15,
            'data' => [
                'current' => time(),
                'id' => $this->id,
                'email' => $this->email
            ]
        ];
        $jwt = JWT::encode($token, $key);
        return Utils::localeUrl('confirm') . "?" . http_build_query(['code' => urlencode($jwt)]);
    }

    public function getResetPasswordUrlAttribute()
    {
        $key = env('APP_KEY');
        $key = env('APP_KEY');
        $token = [
            'exp' => time() + 60 * 15,
            'data' => [
                'current' => time(),
                'id' => $this->id,
                'requester_email' => $this->email
            ]
        ];
        $jwt = JWT::encode($token, $key);
        return Utils::localeUrl('reset') . "?" . http_build_query(['code' => urlencode($jwt)]);
    }

    public function getUrlAttribute()
    {
        return url('users', [$this->username]);
    }

    public function getUrlEsAttribute()
    {
        return url('es/usuarios', [$this->username]);
    }

    public function getLocaleUrlAttribute()
    {
        $locale = app('translator')->getLocale();
        if ($locale === 'es') {
            if (!empty($this->url_es)) {
                return $this->url_es;
            }
        }
        return $this->url;
    }

    public function getNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function isValidConfirmCode($code)
    {
        $key = env('APP_KEY');
        try {
            $credential = JWT::decode($code, $key, ['HS256']);
            return ($credential->data->email === $this->email);
        } catch (ExpiredException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function isValidResetCode($code)
    {
        $key = env('APP_KEY');
        try {
            $credential = JWT::decode($code, $key, ['HS256']);
            return ($credential->data->requester_email === $this->requester_email);
        } catch (ExpiredException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function findByCode($code)
    {
        $key = env('APP_KEY');
        try {
            $credential = JWT::decode($code, $key, ['HS256']);
            return User::find($credential->data->id);
        } catch (ExpiredException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}
