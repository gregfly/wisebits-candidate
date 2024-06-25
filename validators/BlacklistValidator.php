<?php
namespace validators;

use models\IModel;

/**
 * BlacklistValidator
 *
 * @author Volkov Grigorii
 */
class BlacklistValidator extends Validator
{
    public function __construct(
        public array $words,
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): bool
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $value = mb_strtolower((string)$value);
        foreach ($this->words as $word) {
            if (str_contains($value, mb_strtolower($word))) {
                $model->addError($attribute, $this->errorMessage);
                return false;
            }
        }
        return true;
    }
}
