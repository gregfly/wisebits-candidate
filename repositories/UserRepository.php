<?php
namespace repositories;

use models\User;

/**
 * UserRepository
 *
 * @author Volkov Grigorii
 */
class UserRepository implements IRepository
{
    public function findBy(string $attribute, string $val): User|null
    {
        return null;
    }

    public function save(User $entity): void
    {
        return;
    }
}
