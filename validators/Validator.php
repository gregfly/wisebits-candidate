<?php
namespace validators;

use models\IModel;

abstract class Validator
{
    protected function isEmpty($value): bool
    {
        return $value === null || $value === [] || $value === '';
    }

    abstract public function validate(IModel $model, string $attribute): bool;
}
