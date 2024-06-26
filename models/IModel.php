<?php
namespace models;

interface IModel
{
    public function getAttribute(string $name): mixed;
    public function setAttribute(string $name, mixed $value): void;
}
