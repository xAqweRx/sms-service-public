<?php


namespace App\Exceptions;


class JsonDecodeException extends \Exception
{
    private $input;
    private $errorCode;

    public function __construct($input)
    {
        $this->input = $input;
        $this->errorCode = json_last_error();
        parent::__construct(json_last_error_msg());
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
