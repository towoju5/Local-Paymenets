<?php

namespace Towoju5\Localpayments;
use Towoju5\Localpayments\Exception\PayoutException;

class Payout
{
    public $local;
    public function __construct(){
        $this->local = new Localpayments();
    }

    public function availableBanks(){
        $banks = $this->local->bank()->search(); 
        return $banks;
    }

    public function createPayout(array $params){
        $process = [];
        $requiredArrayKeys = ["paymentMethod", "externalId", "country", "amount", "currency", "accountNumber", "conceptCode", "merchant", "beneficiary"];
        
        foreach ($requiredArrayKeys as $key) {
            if (!array_key_exists($key, $params)) {
                throw new PayoutException("$key is required", 500, $key);
            }
        }
        $endpoint = "/api/payout";
        $local = new Localpayments;
        $process = $local->curl($endpoint, "POST", $params);
        
        if(!is_array($process)) {
            $process = json_encode($process, true);
        } 
        
        // echo json_encode($process, true); exit;

        if(isset($process['status']['code']) && $process['status']['code'] == 100 && $process['status']['description']) {
            return [
                "message" => $process['status']['detail']
            ];
        }

        if(isset($process['status']['code']) && $process['status']['code'] != 100 && isset($process['errors'])) {
            $arr = [
                "message" => $process['errors'],
                "error" => $process['status']['detail'],
            ];

            $process['error'] = $arr;
            return $process;
        }

        return $process;
    }

    public function payoutStatus($externalId)
    {
        try {
            $endpoint = "/transactions/{$externalId}";
            $request = $this->local->curl($endpoint, "GET");
            return $request;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
}
