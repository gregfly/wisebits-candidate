<?php
namespace loggers;

/**
 * EchoLogger
 *
 * @author Volkov Grigorii
 */
final class EchoLogger implements ILogger
{
    public function __construct(
        public LogLevel $minLevel = LogLevel::Trace,
    ) {}

    public function log(LogLevel $lvl, string $message): void
    {
        if ($lvl->value >= $this->minLevel->value) {
            echo '[' . $lvl->toString() . '] ' . $message . PHP_EOL;
        }
    }
}
