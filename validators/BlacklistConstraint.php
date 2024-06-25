<?php
namespace validators;

use models\IModel;

/**
 * BlacklistConstraint
 *
 * @author Volkov Grigorii
 */
class BlacklistConstraint extends Constraint
{
    public function __construct(
        public array $words,
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): true|string
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $value = mb_strtolower((string)$value);
        foreach ($this->words as $word) {
            if (str_contains($value, mb_strtolower($word))) {
                return $this->errorMessage;
            }
        }
        return true;
    }
}
