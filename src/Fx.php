<?php

namespace Towoju5\Localpayments;

class Fx
{
    // Build your next great package.
    public function __construct(){}

    /**
     * Get a exhange rate quote for payin or payout
     * @param mixed type -> payin | payout
     * @param mixed sourceCurrency
     * @param mixed quoteCurrency
     * @param mixed accountNumber
     */
    public function getQuote($param){
        $endpoint = "/fx/currency-exchange/";
        $method = "GET";
        $local = new Localpayments;
        $request = $local->curl($endpoint, $method, $param);
        return $request;
    }
}
