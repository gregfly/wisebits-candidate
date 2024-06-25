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
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): bool
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            $model->addError($attribute, $this->errorMessage);
            return false;
        }
        return true;
    }
}
