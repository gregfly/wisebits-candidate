<?php
namespace db;

interface IDatabase
{
    public function fetchAll(string $query, array $params = []): array;
    public function fetchOne(string $query, array $params = []): mixed;
    public function execute(string $query, array $params = []): int;
    public function getLastInsertId(): mixed;
}
