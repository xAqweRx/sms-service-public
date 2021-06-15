<?php


namespace App\Helpers;


use App\Exceptions\JsonDecodeException;

class JsonHelper
{
    /**
     * @param string $input
     * @param bool $assoc
     * @return mixed
     * @throws JsonDecodeException
     */
    public static function decode(string $input, $assoc = false) {
        $obj = json_decode($input, $assoc);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new JsonDecodeException($input);
        }

        return $obj;
    }
}
