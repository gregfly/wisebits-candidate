<?php
namespace base;

/**
 * ConsoleApplication
 *
 * @author Volkov Grigorii
 */
class ConsoleApplication extends BaseApplication
{
    protected function resolveRequestUri(): string
    {
        return $_SERVER['argv'];
    }
}
