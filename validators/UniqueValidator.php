<?php
namespace validators;

use models\IModel;

/**
 * UniqueValidator
 *
 * @author Volkov Grigorii
 */
class UniqueValidator extends Validator
{
    public function __construct(
        public IModel $model,
        public string $attribute,
        public string $tableName,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = $this->getModelValue();
        if ($value === null || $value === [] || $value === '') {
            $this->addModelError($this->errorMessage);
            return false;
        }
        return true;
    }
}
