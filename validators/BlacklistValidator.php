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
        public IModel $model,
        public string $attribute,
        public array $words,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = $this->getModelValue();
        if ($this->isEmpty($value)) {
            return true;
        }
        $value = mb_strtolower((string)$value);
        foreach ($this->words as $word) {
            if (str_contains($value, mb_strtolower($word))) {
                $this->addModelError($this->errorMessage);
                return false;
            }
        }
        return true;
    }
}
