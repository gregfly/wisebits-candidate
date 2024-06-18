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

    public static function findOne($id): null|static
    {
        $db = static::getDb();
        $query = 'SELECT * FROM ' . static::getTableName() . ' WHERE ' . static::primaryKey() . '=:id';
        $params = [':id' => $id];
        $row = $db->fetchOne($query, $params);
        if (!$row) {
            return null;
        }
        return new static($row);
    }

    abstract public static function primaryKey(): string;
    abstract public static function getTableName(): string;
    abstract public static function getDb(): IDatabase;
}
