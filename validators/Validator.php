<?php
namespace validators;

use models\IModel;

/**
 * Validator
 *
 * @author Volkov Grigorii
 */
class Validator implements IValidator
{
    private $contraints = [];
    private $errors = [];

    public function __construct() {}

    public function addContraint(string $attribute, Constraint $v): static
    {
        $this->contraints[$attribute][] = $v;
        return $this;
    }

    public function addError(string $name, string $message): void
    {
        $this->errors[$name][] = $message;
    }

    public function getErrors(?string $name = null): array
    {
        return $name? ($this->errors[$name] ?? []) : $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }

    public function validate(IModel $model): bool
    {
        $this->clearErrors();
        foreach ($this->contraints as $attribute => &$contraints) {
            foreach ($contraints as &$contraint) {
                $valid = $contraint->validate($model, $attribute);
                if ($valid !== true) {
                    $this->addError($attribute, $valid);
                }
            }
        }
        return !$this->hasErrors();
    }
}
