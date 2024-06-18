<?php
namespace models;

use validators\Validator;

interface IModel
{
    /**
     * @return \Generator|Validator[]
     */
    public function getValidators(): \Generator;
    public function validate(): bool;

    public function getErrors(): array;
    public function hasErrors(): bool;
    public function addError(string $name, string $message): void;

    public function getAttribute(string $name): mixed;
    public function setAttribute(string $name, mixed $value): void;
}
