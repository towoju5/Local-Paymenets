<?php

namespace Towoju5\Localpayments;

use Exception;
use Illuminate\Support\Facades\Http;

class Localpayments
{
    public $apiUsername, $apiPassword;


    public function __construct()
    {
        $this->apiUsername = $_ENV["LOCALPAYMENT_USERNAME"] ?? getenv("LOCALPAYMENT_USERNAME");
        $this->apiPassword = $_ENV["LOCALPAYMENT_PASSWORD"] ?? getenv("LOCALPAYMENT_PASSWORD");
    }

    /**
     * Bank related queries
     */
    public static function bank(array $config = [])
    {
        $bank = new Bank();
        return $bank;
    }

    public function payin(array $config = [])
    {
        $payin = new Payin();
        return $payin;
    }

    public function payout(array $config = [])
    {
        $payout = new Payout();
        return $payout;
    }

    public function fx(array $config = [])
    {
        $fx = new Fx();
        return $fx;
    }

    public function paymentStatus($externalId)
    {
        try {
            $endpoint = "/transactions/{$externalId}";
            $request = $this->curl($endpoint, "GET");
            return $request;
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function validation()
    {
        $validation = new Validation();
        return $validation;
    }

    private function getAccessToken()
    {
        $token_url = getenv('LOCALPAYMENTS_BASE_URL') . "/api/token/";
        $auth = [
            "username" => $this->apiUsername,
            "password" => $this->apiPassword
        ];
        $result = Http::post($token_url, $auth)->json();
        // if result is not array convert to array
        if(!is_array($result)) {
            $result = json_decode($result, true);
        }
       
        if(isset($result['access'])) {
            return $result['access'];
        } else {
            return ['error' => $result]; //throw new Exception('No access token returned');
        }
    }

    public function curl(string $endpoint, string $method = 'post', array $body = [])
    {
        try {
            $url = getenv('LOCALPAYMENTS_BASE_URL') . $endpoint;
            $result = $this->getAccessToken();
            // if error generating token return error
            if(isset($result['error'])) {
                return ['error' => $result['error']];
            }
            if(isset($result)) {
                if(strtolower($method) == 'post') {
                    $request = Http::withToken($result)->post($url, $body)->json();
                } else if(strtolower($method) == 'get') {
                    $request = Http::withToken($result)->get($url)->json();
                } else {
                    $request = ['error' => "Error: invalid method, please contact support"];
                }
            }
            if(!is_array($result)) {
                $result = json_decode($result, true);
            }
            return ($request);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode(), $th);
        }
    }
}
