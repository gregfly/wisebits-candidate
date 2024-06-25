<?php
namespace validators;

use models\IModel;

/**
 * RequiredConstraint
 *
 * @author Volkov Grigorii
 */
class RequiredConstraint extends Constraint
{
    public function __construct(
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): true|string
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return $this->errorMessage;
        }
        return true;
    }
}
