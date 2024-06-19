<?php
namespace models;

use exceptions\InvalidAttributeException;

/**
 * BaseModel
 *
 * @author Volkov Grigorii
 */
abstract class BaseModel implements IModel
{
    private $attributes = [];
    private $errors = [];

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

    public function addError(string $name, string $message): void
    {
        $this->errors[$name][] = $message;
    }

    public function getValidators(): \Generator
    {
        return [];
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

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function validate(): bool
    {
        foreach ($this->getValidators() as $validator) {
            $validator->validate();
        }
        return !$this->hasErrors();
    }
}
