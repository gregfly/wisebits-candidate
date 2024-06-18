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
        public IModel $model,
        public string $attribute,
        public string $operator,
        public string $compareAttribute,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = $this->getModelValue();
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
            $this->addModelError($this->errorMessage);
            return false;
        }
        return true;
    }
}
