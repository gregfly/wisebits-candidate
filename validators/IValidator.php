<?php
namespace validators;

use models\IModel;
use repositories\IRepository;

/**
 * IValidator
 *
 * @author Volkov Grigorii
 */
interface IValidator
{
    public function addError(string $name, string $message): void;
    public function getErrors(?string $name = null): array;
    public function hasErrors(): bool;
    public function clearErrors(): void;
    public function validate(IModel $model): bool;

    public function addContraint(string $attribute, Constraint $v): static;
    public function addRequired(string $attribute, string $errorMessage = ''): static;
    public function addRegEx(string $attribute, string $pattern, string $errorMessage = ''): static;
    public function addLength(string $attribute, ?int $min = null, string $minErrorMessage = '', ?int $max = null, string $maxErrorMessage = ''): static;
    public function addBlacklist(string $attribute, array $words, string $errorMessage = ''): static;
    public function addUnique(string $attribute, IRepository $repository, string $errorMessage = ''): static;
    public function addEmail(string $attribute, string $errorMessage = ''): static;
    public function addDateTime(string $attribute, string $errorMessage = ''): static;
    public function addCompare(string $attribute, string $operator, string $compareAttribute, string $errorMessage = ''): static;
}
