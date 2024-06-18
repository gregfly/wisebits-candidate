<?php
namespace validators;

/**
 * RegExValidator
 *
 * @author Volkov Grigorii
 */
class RegExValidator extends Validator
{
    const PATTERN_LETTER_OR_NUMBER = '#^[a-z0-9]+$#';
    const PATTERN_EMAIL = "/(?:[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*|\"(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])/";

    public function __construct(
        public \models\IModel $model,
        public string $attribute,
        public string $pattern,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = (string)$this->getModelValue();
        if (!preg_match($this->pattern, $value)) {
            $this->addModelError($this->errorMessage);
            return false;
        }
        return true;
    }
}
