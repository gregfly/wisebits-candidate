<?php
namespace repositories;

/**
 * IRepository
 *
 * @author Volkov Griorii
 */
interface IRepository
{
    public function findBy(string $attribute, string $val): IEntity|null;
    public function save(IEntity $entity): void;
}
