<?php

namespace Towoju5\Localpayments;

use Exception;

class Localpayments
{
    public $apiUsername, $apiPassword;


    public function __construct()
    {
        $this->apiUsername = $_ENV["LOCALPAYMENT_USERNAME"] ?? getenv("LOCALPAYMENT_USERNAME");
        $this->apiPassword = $_ENV["LOCALPAYMENT_PASSWORD"] ?? getenv("LOCALPAYMENT_PASSWORD");
    }

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
            throw $th;
        }
    }

    public function validation()
    {
        $validation = new Validation();
        return $validation;
    }

    private function apiUsername()
    {
        return $this->apiUsername();
    }

    private function apiPassword()
    {
        return $this->apiPassword();
    }

    public function getAccessToken()
    {
        $token_url = $_ENV['LOCALPAYMENTS_BASE_URL'] . "/api/token/";
        $auth = [
            "username" => $this->apiUsername,
            "password" => $this->apiPassword
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($auth));
        $headers = array();
        $headers[] = 'X-Version: LPV3';
        $headers[] = 'X-Forwarded-For: 1';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = to_array(curl_exec($ch));
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if(isset($result['access'])) return $result['access'];
        else throw new Exception('No access token returned');
    }

    public function curl(string $endpoint, string $method = 'post', array $body = [])
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_ENV['LOCALPAYMENTS_BASE_URL'] . $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if ($method == 'post') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            } elseif ($method == 'get') {
                if (!empty($body)) {
                    $query_string = http_build_query($body);
                    $endpoint .= '?' . $query_string;
                }
            }

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer '.$this->getAccessToken();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                return ['error' => 'Error:' . curl_error($ch)];
            }
            curl_close($ch);

            return to_array($result);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode(), $th);
        }
    }
}



// 
