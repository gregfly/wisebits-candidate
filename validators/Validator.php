<?php
namespace validators;

use models\IModel;
use validators\RegExConstraint;
use validators\LengthConstraint;
use validators\RequiredConstraint;
use validators\CompareConstraint;
use validators\BlacklistConstraint;
use validators\UniqueConstraint;

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

    public function addContraint(string $attribute, Constraint $v): static
    {
        $this->contraints[$attribute][] = $v;
        return $this;
    }

    public function addBlacklist(string $attribute, array $words, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new BlacklistConstraint($words, $errorMessage));
    }

    public function addCompare(string $attribute, string $operator, string $compareAttribute, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new CompareConstraint($operator, $compareAttribute, $errorMessage));
    }

    public function addDateTime(string $attribute, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new RegExConstraint(RegExConstraint::PATTERN_DATETIME, $errorMessage));
    }

    public function addEmail(string $attribute, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new RegExConstraint(RegExConstraint::PATTERN_EMAIL, $errorMessage));
    }

    public function addLength(string $attribute, ?int $min = null, string $minErrorMessage = '', ?int $max = null, string $maxErrorMessage = ''): static
    {
        return $this->addContraint($attribute, new LengthConstraint($min, $minErrorMessage, $max, $maxErrorMessage));
    }

    public function addRegEx(string $attribute, string $pattern, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new RegExConstraint($pattern, $errorMessage));
    }

    public function addRequired(string $attribute, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new RequiredConstraint($errorMessage));
    }

    public function addUnique(string $attribute, \repositories\IRepository $repository, string $errorMessage = ''): static
    {
        return $this->addContraint($attribute, new UniqueConstraint($repository, $errorMessage));
    }
}
