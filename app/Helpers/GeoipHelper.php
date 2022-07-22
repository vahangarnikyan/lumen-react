<?php

namespace App\Helpers;

use App\Geoip;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;

class GeoipHelper
{
    public $apiKeys;
    protected $geoip = [];

    public function __construct()
    {
        $this->apiKeys = [
            'd78c23c908f443ebaaf1f0f03b381deb'
        ];
    }

    protected function getGeoipData($ip)
    {
        foreach ($this->apiKeys as $apiKey) {
            $client = new Client([
                'base_uri' => "https://api.ipgeolocation.io/ipgeo?apiKey={$apiKey}&ip={$ip}",
                'proxy' => getenv('HTTP_PROXY'),
            ]);
            try {
                $response = $client->request('GET');
                $originals = json_decode($response->getBody(), true);
                if ($response->getStatusCode() !== 200) {
                    break;
                }
                $originals['time_zone'] = $originals['time_zone']['name'];
                $originals['currency'] = $originals['currency']['code'];
                return $originals;
            } catch (\Exception $e) {
                //
            }
        }
        return null;
    }

    public function getGeoip($ip = null)
    {
        if (empty($ip)) {
            if (app()->environment('local')) {
                $ip = '183.182.114.206';
            } else {
                $ip = Request::ip();
            }
        }
        if (!isset($this->geoip[$ip])) {
            $geoip = Geoip::find($ip);
            if ($geoip === null) {
                $geoipData = $this->getGeoipData($ip);
                if ($geoipData) {
                    $geoip = Geoip::create($geoipData);
                }
            }
            $this->geoip[$ip] = $geoip;
        }
        
        return $this->geoip[$ip];
    }
}
