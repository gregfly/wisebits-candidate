<?php
namespace db;

use PDO;
use base\Glob;

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
            $stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
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
            $stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
