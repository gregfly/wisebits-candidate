<?php
namespace helpers;

/**
 * Json
 *
 * @author Volkov Grigorii
 */
class Json
{
    public static function encode(mixed $value): string
    {
        return json_encode($value);
    }

    public static function decode(?string $json): mixed
    {
        if ($json === null) {
            return $json;
        }
        return json_decode($json, true);
    }
}
