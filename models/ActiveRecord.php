<?php
namespace models;

use db\IDatabase;

/**
 * ActiveRecord
 *
 * @author Volkov Grigorii
 */
abstract class ActiveRecord extends BaseModel
{
    public function getPrimaryKey(): mixed
    {
        return $this->getAttribute(static::primaryKey());
    }

    public function isNewRecord(): bool
    {
        return $this->getPrimaryKey() === null;
    }

    public static function exists(string $condition, array $params = []): array
    {
        $db = static::getDb();
        $query = 'SELECT * FROM ' . static::getTableName() . ($condition? ' WHERE ' . $condition : '');
        $row = $db->fetchOne($query, $params);
        if (!$row) {
            return false;
        }
        return true;
    }

    public static function findOne($id): ?static
    {
        $db = static::getDb();
        $query = 'SELECT * FROM ' . static::getTableName() . ' WHERE ' . static::primaryKey() . '=:id';
        $params = [':id' => $id];
        $row = $db->fetchOne($query, $params);
        if (!$row) {
            return null;
        }
        return static::populateRow($row);
    }

    public function setAttributes(array $row): void
    {
        foreach ($row as $col => &$val) {
            $this->setAttribute($col, $val);
        }
    }

    protected static function &populateRow(array $row): static
    {
        $model = new static();
        $model->setAttributes($row);
        return $model;
    }

    abstract public static function primaryKey(): string;
    abstract public static function getTableName(): string;
    abstract public static function getDb(): IDatabase;
}
