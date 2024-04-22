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
        $requiredArrayKeys = ["paymentMethod", "externalId", "country", "amount", "currency", "accountNumber", "conceptCode", "merchant", "beneficiary"];
        // check if all rerquired array exists
        foreach ($requiredArrayKeys as $key) {
            if (!array_key_exists($key, $params)) {
                throw new PayoutException("$key is required", 500, $key);
            }
        }
        $endpoint = "/payout";
        $process = $this->local->curl($endpoint, "POST", $params);
        return (array)$process;
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
