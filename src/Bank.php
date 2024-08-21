<?php

namespace Towoju5\Localpayments;

class Bank
{
    // Build your next great package.
    public function __construct()
    {
    }

    public function accountType($country)
    {
        $arr = [
            "C" => "Checking Account",
            "S" => "Savings Account",
        ];

        switch ($country) {
            case 'PEN':
            case 'peru':
                $arr['M'] = "Maestra Account";
                break;

            case 'CLP':
            case 'chile':
                $arr['V'] = "Vista Account";
                $arr['R'] = "RUT Account";
                break;

            default:
                null;
                break;
        }

        return $arr;
    }

    /**
     * Acceptable parameters are 
     */
    public function search(array $params = [])
    {
        $endpoint = "/resources/banks";
        $local = new Localpayments;
        $request = $local->curl($endpoint, "GET", $params);
        return $request;
    }

    public function createVirtualAccount(array $params = [])
    {        
        $endpoint = "/api/virtual-account";
        $local = new Localpayments;
        $request = $local->curl($endpoint, "POST", $params);
        return $request;
    }

    /**
     * @param string $externalId
     * 
     * @return array
     */
    public function getVirtualAccount($externalId)
    {        
        $endpoint = "/api/virtual-account/$externalid";
        $local = new Localpayments;
        $request = $local->curl($endpoint, "GET");
        return $request;
    }
}
