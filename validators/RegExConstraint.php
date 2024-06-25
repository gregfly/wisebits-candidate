<?php
namespace validators;

use models\IModel;

/**
 * RegExConstraint
 *
 * @author Volkov Grigorii
 */
class RegExConstraint extends Constraint
{
    public const PATTERN_LETTER_OR_NUMBER = '#^[a-z0-9]+$#i';
    public const PATTERN_DATETIME = '#^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$#i';
    public const PATTERN_EMAIL = "/(?:[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*|\"(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])/";

    public function __construct(
        public string $pattern,
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
        if (!preg_match($this->pattern, (string)$value)) {
            return $this->errorMessage;
        }
        return true;
    }
}
