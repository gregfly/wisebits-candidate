<?php
namespace base;

use loggers\LogLevel;
use loggers\ILogger;

/**
 * Glob
 *
 * @author Volkov Grigorii
 */
class Glob
{
    public static ?\base\BaseApplication $app = null;

    public static function info(string $message): void
    {
        self::getLogger()?->log(LogLevel::Info, $message);
    }

    public static function error(string $message): void
    {
        self::getLogger()?->log(LogLevel::Error, $message);
    }

    public static function trace(string $message): void
    {
        self::getLogger()?->log(LogLevel::Trace, $message);
    }

    public static function getLogger(): ?ILogger
    {
        return self::$app?->getLogger();
    }
}
