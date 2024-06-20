<?php
namespace db;

use models\BaseModel;

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

    public static function exists(string $condition, array $params = []): bool
    {
        $db = static::getDb();
        $query = 'SELECT 1 FROM ' . static::getTableName() . ($condition? ' WHERE ' . $condition : '');
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

    public static function findOneForUpdate($id): ?static
    {
        $db = static::getDb();
        $query = 'SELECT * FROM ' . static::getTableName() . ' WHERE ' . static::primaryKey() . '=:id FOR UPDATE';
        $params = [':id' => $id];
        $row = $db->fetchOne($query, $params);
        if (!$row) {
            return null;
        }
        return static::populateRow($row);
    }

    public function getAttributes(array $names = []): array
    {
        if (!$names) {
            $names = $this->attributeNames();
        }
        $values = [];
        foreach ($names as &$name) {
            $values[$name] = $this->getAttribute($name);
        }
        return $values;
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

    public function save($runValidation = true): bool
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }
        if ($this->isNewRecord()) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function insert(): bool
    {
        $db = static::getDb();
        $p = ':p';
        $params = [];
        $cols = [];
        $vals = [];
        foreach ($this->attributeNames() as $i => $attr) {
            $val = $this->getAttribute($attr);
            if ($val === null) {
                continue;
            }
            $cols[] = $attr;
            $vals[] = $p . $i;
            $params[$p . $i] = $val;
        }
        $query = 'INSERT INTO ' . static::getTableName() . ' (' . implode(', ', $cols) . ') VALUES (' . implode(', ', $vals) . ')';
        if ($db->execute($query, $params) > 0) {
            $this->setAttribute(static::primaryKey(), $db->getLastInsertId());
            $this->afterSave(true);
            return true;
        }
        return false;
    }

    public function update(): bool
    {
        $db = static::getDb();
        $p = ':p';
        $params = [];
        $set = [];
        foreach ($this->attributeNames() as $i => $attr) {
            $val = $this->getAttribute($attr);
            $set[] = $attr . '=' . $p . $i;
            $params[$p . $i] = $val;
        }
        $query = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $set) . ' WHERE ' . static::primaryKey() . '=:id';
        $params[':id'] = $this->getPrimaryKey();
        if ($db->execute($query, $params) > 0) {
            $this->afterSave(false);
            return true;
        }
        return false;
    }

    protected function afterSave(bool $insert): void {}

    abstract public static function primaryKey(): string;
    abstract public static function getTableName(): string;
    abstract public static function getDb(): IDatabase;
}
