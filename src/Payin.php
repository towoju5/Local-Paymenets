<?php

namespace Towoju5\Localpayments;
use Towoju5\Localpayments\Exception\PayoutException;

class Payin
{
    public $endpoint;

    public $local;
    public function __construct(){
        $this->local = new Localpayments();
        $this->endpoint = "/payin";
    }

    public function init(array $data)
    {
        
    }

    public function bankTransfer(array $data) 
    {
        try {
            // $objectArray = 
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function debitCard()
    {
        //
    }

    

    public function payinStatus($externalId)
    {
        try {
            $endpoint = "/transactions/{$externalId}";
            $request = $this->local->curl($endpoint, "GET");
            return $request;
        } catch (\Throwable $th) {
            throw new PayoutException($th);
        }
    }
}