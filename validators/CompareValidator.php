<?php
namespace validators;

use models\IModel;

/**
 * CompareValidator
 *
 * @author Volkov Grigorii
 */
class CompareValidator extends Validator
{
    public function __construct(
        public string $operator,
        public string $compareAttribute,
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): bool
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $compareValue = $this->model->getAttribute($this->compareAttribute);
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
            $model->addError($attribute, $this->errorMessage);
            return false;
        }
        return true;
    }
}
