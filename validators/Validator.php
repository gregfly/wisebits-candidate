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

    protected function addModelError(string $message): mixed
    {
        return $this->model->addError($this->attribute, $message);
    }

    abstract public function validate(): bool;
}
