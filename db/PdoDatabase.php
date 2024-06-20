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

    public function open(): void
    {
        if ($this->isActive()) {
            return;
        }
        try {
            Glob::info('Open connection ' . $this->dsn);
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function close(): void
    {
        if ($this->isActive()) {
            while ($this->isActiveTransaction()) {
                $this->rollback();
            }
            Glob::info('Close connection ' . $this->dsn);
            $this->pdo = null;
        }
    }

    public function isActive(): bool
    {
        return $this->pdo !== null;
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
            throw new DatabaseException($e->getMessage(), (int)$e->getCode(), $e);
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
            throw new DatabaseException($e->getMessage(), (int)$e->getCode(), $e);
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
            throw new DatabaseException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public function getLastInsertId(): mixed
    {
        $this->open();
        try {
            return $this->pdo->lastInsertId(null);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage(), (int)$e->getCode(), $e);
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

    protected int $transactionCounter = 0;

    public function isActiveTransaction(): bool
    {
        return $this->transactionCounter > 0;
    }

    public function beginTransaction(): void
    {
        $this->open();
        if (!$this->transactionCounter++) {
            $this->pdo->beginTransaction();
            return;
        }
        $this->exec('SAVEPOINT savepoint' . $this->transactionCounter);
    }

    public function commit(): void
    {
        $this->open();
        if ($this->transactionCounter <= 0) {
            throw new DatabaseException('Transaction is not progress');
        }
        if (!--$this->transactionCounter) {
            $this->pdo->commit();
        }
    }

    public function rollback(): void
    {
        $this->open();
        if ($this->transactionCounter <= 0) {
            throw new DatabaseException('Transaction is not progress');
        }
        if (--$this->transactionCounter) {
            $this->exec('ROLLBACK TO savepoint' . ($this->transactionCounter + 1));
            return;
        }
        $this->pdo->rollBack();
    }
}
