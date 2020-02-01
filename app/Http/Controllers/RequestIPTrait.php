<?php

namespace App\Http\Controllers;

use App\Notifications\GenericException;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * If you are under a load balancer, Laravel's \Request::ip() always return the balancer's IP.
 * This trait contains a method that returns the real client ip.
 */
trait RequestIP
{
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe

                    if (env('APP_DEBUG')) {
                        //In DEBUG mode, allow client IPs like 127.0.0.1
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) !== false) {
                            return $ip;
                        }
                    } else {
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                            return $ip;
                        }
                    }
                }
            }
        }
    }

    public function ip_2_city_country($ip)
    {
        if ($ip == '127.0.0.1') {
            return [
                'city' => 'Islamabad',
                'country' => 'Pakistan', //Note that we are not using $Loc['country'] because that one contains country code, not country name.
            ];
        } else {
            $http = new \GuzzleHttp\Client;

            try {
                //get token first
                $response = $http->post("https://ipapi.co/$ip/json/");
                $Loc = json_decode($response->getBody()->getContents(), true);
                return [
                    'city' => $Loc['city'],
                    'country' => $Loc['country_name'], //Note that we are not using $Loc['country'] because that one contains country code, not country name.
                ];
            } catch (Exception $e) {
                (new SlackAgent())->notify(new GenericException($ip, request()->user(), $e));
                return null;
            }
        }
    }
}
