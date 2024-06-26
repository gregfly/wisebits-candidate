<?php
namespace tests;

/**
 * TestCase
 *
 * @author Volkov Grigorii
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    public static $params;

    public static function getParam($name, $default = null): mixed
    {
        if (static::$params === null) {
            static::$params = require __DIR__ . '/data/config.php';
        }

        return isset(static::$params[$name]) ? static::$params[$name] : $default;
    }
}
