<?php

namespace Towoju5\Localpayments;

class Validation
{
    // Build your next great package.
    public function __construct()
    {
    }

    /**
     * @param string document.id => 52993635000130,
     * @param string document.type => CNPJ
     * @param string country => BRA
     *  
     */
    public function validate_document($param)
    {
        $endpoint = "/validation/document";
        $method = "POST";

        // check for account type
        $local = new Localpayments();
        $api_call = $local->curl($endpoint, $method, (array) $param);
        return $api_call;
    }

    /**
     * @param string country
     * @param string bank.code
     * @param string branch.code
     * @param string type
     * @param string bank.account.number
     * 
     * Branch code is Only mandatory for brazilian's bank account. 
     * It lenght should be between 1 to 5 characters
     */
    public function validate_bank(array $param)
    {
        $endpoint = "/validation/bank";
        $method = "POST";


        // check for account type
        $local = new Localpayments();
        $account_type = $local->bank()->accountType($param['country']);
        if (!in_array($param['type'], $account_type)) {
            throw new \Exception('Invalid account type provided');
        }
        $api_call = $local->curl($endpoint, $method, (array) $param);
        return $api_call;
    }

    /**
     * @param string bin
     */
    public function validate_bin($param)
    {
        $endpoint = "/resources/bin-validation";
        $method = "GET";
        // check for account type
        $local = new Localpayments();
        $account_type = $local->bank()->accountType($param['country']);
        if(!in_array($param['type'], $account_type)) {
            throw new \Exception('Invalid account type provided');
        }
        $api_call = $local->curl($endpoint, $method, (array)$param);
        return $api_call;
    }
}
