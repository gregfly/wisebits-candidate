<?php
namespace validators;

use models\IModel;

/**
 * CompareConstraint
 *
 * @author Volkov Grigorii
 */
class CompareConstraint extends Constraint
{
    public function __construct(
        public string $operator,
        public string $compareAttribute,
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): true|string
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $compareValue = $model->getAttribute($this->compareAttribute);
        $op = match ($this->operator) {
            '==' => $value == $compareValue,
            '===' => $value === $compareValue,
            '!=' => $value != $compareValue,
            '!==' => $value !== $compareValue,
            '>' => $value > $compareValue,
            '>=' => $value >= $compareValue,
            '<' => $value < $compareValue,
            '<=' => $value <= $compareValue,
            default => false,
        };
        if (!$op) {
            return $this->errorMessage;
        }
        return true;
    }
}
