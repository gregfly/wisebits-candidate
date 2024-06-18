<?php
namespace db;

interface IDatabase
{
    public function fetchAll(string $query, array $params = []): array;
    public function fetchOne(string $query, array $params = []): mixed;
}
