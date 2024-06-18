<?php
namespace validators;

use models\IModel;

/**
 * RequiredValidator
 *
 * @author Volkov Grigorii
 */
class RequiredValidator extends Validator
{
    public function __construct(
        public IModel $model,
        public string $attribute,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = $this->getModelValue();
        if ($this->isEmpty($value)) {
            $this->addModelError($this->errorMessage);
            return false;
        }
        return true;
    }
}
