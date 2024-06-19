<?php
namespace unit;

use base\Glob;
use db\IDatabase;
use loggers\ILogger;
use loggers\LogLevel;

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

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    protected function mockApplication(string $appClass = 'base\ConsoleApplication'): void
    {
        new $appClass($this->prepareDb(), $this->prepareLogger());
    }

    protected function prepareDb($open = true): IDatabase
    {
        $cfg = $this->getParam('db');
        $fixture = $cfg['fixture'] ?? null;
        $db = new \db\PdoDatabase($cfg['dsn'] ?? '', $cfg['username'] ?? '', $cfg['password'] ?? '');
        if ($open && file_exists($fixture)) {
            $lines = explode(';', file_get_contents($fixture));
            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    $db->execute($line);
                }
            }
        }
        return $db;
    }

    protected function prepareLogger(): ILogger
    {
        return new \loggers\EchoLogger(minLevel: LogLevel::Error);
    }

    protected function destroyApplication(): void
    {
        if (Glob::$app) {
            $this->getDatabase()->close();
            Glob::$app = null;
        }
    }

    protected function getDatabase(): IDatabase
    {
        return Glob::$app->getDb();
    }
}
