<?php
namespace loggers;

interface ILogger
{
    public function log(LogLevel $lvl, string $message): void;
}
