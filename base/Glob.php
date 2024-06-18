<?php
namespace base;

use loggers\LogLevel;

/**
 * Glob
 *
 * @author Volkov Grigorii
 */
class Glob
{
    public static \base\BaseApplication $app;

    public static function info(string $message): void
    {
        self::$app->getLogger()->log(LogLevel::Info, $message);
    }

    public static function error(string $message): void
    {
        self::$app->getLogger()->log(LogLevel::Error, $message);
    }

    public static function trace(string $message): void
    {
        self::$app->getLogger()->log(LogLevel::Trace, $message);
    }
}
