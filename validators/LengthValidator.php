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
        public ?int $min = null,
        public string $minErrorMessage = '',
        public ?int $max = null,
        public string $maxErrorMessage = '',
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(IModel $model, string $attribute): bool
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $len = is_countable($value)? count($value) : mb_strlen((string)$value);
        if (($this->min !== null) && ($len < $this->min)) {
            $model->addError($attribute, $this->minErrorMessage);
            return false;
        }
        if (($this->max !== null) && ($len > $this->max)) {
            $model->addError($attribute, $this->maxErrorMessage);
            return false;
        }
        return true;
    }
}
