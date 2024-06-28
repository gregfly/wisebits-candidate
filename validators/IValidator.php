<?php
namespace validators;

use models\IModel;

/**
 * IValidator
 *
 * @author Volkov Grigorii
 */
interface IValidator
{
    public function addContraint(string $attribute, Constraint $v): static;
    public function addError(string $name, string $message): void;
    public function getErrors(?string $name = null): array;
    public function hasErrors(): bool;
    public function clearErrors(): void;
    public function validate(IModel $model): bool;
}
