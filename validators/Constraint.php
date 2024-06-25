<?php
namespace validators;

use models\IModel;

abstract class Constraint
{
    protected function isEmpty($value): bool
    {
        return $value === null || $value === [] || $value === '';
    }

    abstract public function validate(IModel $model, string $attribute): true|string;
}
