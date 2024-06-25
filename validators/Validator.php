<?php
namespace validators;

use models\IModel;

/**
 * Validator
 *
 * @author Volkov Grigorii
 */
class Validator
{
    private $constraints = [];
    private $errors = [];

    public function __construct(
        array $contraints = [],
    ) {
        foreach ($contraints as &$contraint) {
            $this->addContraint($contraint[0], $contraint[1]);
        }
    }

    public function addContraint(string $attribute, Constraint $v): static
    {
        $this->contraints[] = [$attribute, $v];
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
        foreach ($this->constraints as &$contraint) {
            $valid = $contraint[1]->validate($model, $contraint[0]);
            if ($valid !== true) {
                $this->addError($contraint[0], $valid);
            }
        }
        return !$this->hasErrors();
    }
}
