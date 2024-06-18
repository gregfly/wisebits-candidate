<?php
namespace base;

use db\IDatabase;
use loggers\ILogger;

/**
 * BaseApplication
 *
 * @author Volkov Grigorii
 */
abstract class BaseApplication
{
    public function __construct(
        protected IDatabase $db,
        protected ILogger $logger,
    ) {}

    private $url;

    public function getUrl(): string
    {
        if ($this->url === null) {
            $this->url = $this->resolveRequestUri();
        }
        return $this->url;
    }

    public function getDb(): IDatabase
    {
        return $this->db;
    }

    public function setDb(IDatabase $db): void
    {
        $this->db = $db;
    }

    public function getLogger(): ILogger
    {
        return $this->logger;
    }

    public function setLogger(IDatabase $logger): void
    {
        $this->logger = $logger;
    }

    abstract protected function resolveRequestUri(): string;
}
