<?php
namespace validators;

use models\IModel;

/**
 * LengthValidator
 *
 * @author Volkov Grigorii
 */
class LengthValidator extends Validator
{
    public function __construct(
        public IModel $model,
        public string $attribute,
        public int $min = null,
        public string $minErrorMessage = '',
        public int $max = null,
        public string $maxErrorMessage = '',
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = $this->getModelValue();
        if ($this->isEmpty($value)) {
            return true;
        }
        $len = mb_strlen((string)$value);
        if (($this->min !== null) && ($len < $this->min)) {
            $this->addModelError($this->minErrorMessage);
            return false;
        }
        if (($this->max !== null) && ($len > $this->max)) {
            $this->addModelError($this->maxErrorMessage);
            return false;
        }
        return true;
    }
}
