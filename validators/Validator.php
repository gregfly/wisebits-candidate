<?php
namespace validators;

use models\IModel;

abstract class Validator
{
    public function __construct(
        public IModel $model,
        public string $attribute,
    ) {}

    protected function getModelValue(): mixed
    {
        return $this->model->getAttribute($this->attribute);
    }

    protected function addModelError(string $message): void
    {
        $this->model->addError($this->attribute, $message);
    }

    protected function isEmpty($value): bool
    {
        return $value === null || $value === [] || $value === '';
    }

    abstract public function validate(): bool;
}
