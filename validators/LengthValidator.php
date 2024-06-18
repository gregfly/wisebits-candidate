<?php
namespace validators;

/**
 * LengthValidator
 *
 * @author Volkov Grigorii
 */
class LengthValidator extends Validator
{
    public function __construct(
        public \models\IModel $model,
        public string $attribute,
        public int $min,
        public string $minErrorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = (string)$this->getModelValue();
        $len = mb_strlen($value);
        if ($len < $this->min) {
            $this->addModelError($this->minErrorMessage);
            return false;
        }
        return true;
    }
}
