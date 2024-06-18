<?php
namespace loggers;

/**
 * EchoLogger
 *
 * @author Volkov Grigorii
 */
class EchoLogger implements ILogger
{
    public function log(LogLevel $lvl, string $message): void
    {
        echo '[' . $lvl->toString() . '] ' . $message . PHP_EOL;
    }
}
