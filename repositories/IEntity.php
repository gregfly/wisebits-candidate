<?php
namespace repositories;

/**
 * IEntity
 *
 * @author Volkov Grigorii
 */
interface IEntity
{
    public static function primaryKey(): string;
    public function attributeNames(): array;
    public function getAttributes(array $names = []): array;
    public function setAttributes(array $row): void;
    public function is(IEntity $other): bool;
}
