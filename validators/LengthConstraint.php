<?php
namespace validators;

use models\IModel;

/**
 * LengthConstraint
 *
 * @author Volkov Grigorii
 */
class LengthConstraint extends Constraint
{
    public function __construct(
        public ?int $min = null,
        public string $minErrorMessage = '',
        public ?int $max = null,
        public string $maxErrorMessage = '',
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): true|string
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $len = is_countable($value)? count($value) : mb_strlen((string)$value);
        if (($this->min !== null) && ($len < $this->min)) {
            return $this->minErrorMessage;
        }
        if (($this->max !== null) && ($len > $this->max)) {
            return $this->maxErrorMessage;
        }
        return true;
    }
}
