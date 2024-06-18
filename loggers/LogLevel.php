<?php
namespace loggers;

enum LogLevel: int
{
    case Trace = 100;
    case Info = 500;
    case Error = 1000;

    public function toString(): string
    {
        return $this->name;
    }
}