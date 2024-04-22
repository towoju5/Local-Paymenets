<?php

namespace Towoju5\Localpayments\Exception;

use Exception;

class PayoutException extends Exception
{
    protected $missingKey;

    public function __construct($message = "", $code = 0, $missingKey = null, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->missingKey = $missingKey;
    }

    public function getMissingKey()
    {
        return $this->missingKey;
    }

    public function errorMessage()
    {
        return 'Error: ' . $this->getMessage() . ', Required Key: ' . $this->getMissingKey();
    }
}
