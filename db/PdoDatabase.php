<?php
namespace db;

use PDO;
use PDOStatement;
use base\Glob;
use exceptions\DatabaseException;

/**
 * PdoDatabase
 *
 * @author Volkov Grigorii
 */
class PdoDatabase implements IDatabase
{
    private ?PDO $pdo = null;

    public function __construct(
        public string $dsn,
        public string $username,
        public string $password,
    ) {}

    protected function open()
    {
        if ($this->pdo !== null) {
            return;
        }
        try {
            Glob::info('Open connection ' . $this->dsn);
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function fetchAll(string $query, array $params = []): array
    {
        $this->open();
        try {
            Glob::trace('Query ' . $query);
            $stmt = $this->createStatement($query, $params);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function fetchOne(string $query, array $params = []): mixed
    {
        $this->open();
        try {
            Glob::trace('Query ' . $query);
            $stmt = $this->createStatement($query, $params);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function execute(string $query, array $params = []): int
    {
        $this->open();
        try {
            Glob::trace('Query ' . $query);
            $stmt = $this->createStatement($query, $params);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getLastInsertId(): mixed
    {
        $this->open();
        try {
            return $this->pdo->lastInsertId(null);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function &createStatement($query, $params): PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt;
    }
}
