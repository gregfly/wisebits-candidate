<?php
namespace models;

use exceptions\InvalidAttributeException;
use repositories\IEntity;

/**
 * EntityModel
 *
 * @author Volkov Grigorii
 */
abstract class EntityModel implements IModel, IEntity
{
    private $attributes = [];

    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => &$val) {
            if (is_numeric($name)) {
                $this->attributes[$val] = null;
            } else {
                $this->attributes[$name] = $val;
            }
        }
    }

    public function getAttribute(string $name): mixed
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        throw new InvalidAttributeException('Атрибут ' . $name . ' не существует');
    }

    public function setAttribute(string $name, mixed $value): void
    {
        if (!array_key_exists($name, $this->attributes)) {
            throw new InvalidAttributeException('Атрибут ' . $name . ' не существует');
        }
        $this->attributes[$name] = $value;
    }

    public function attributeNames(): array
    {
        return array_keys($this->attributes);
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
}
