<?php
namespace repositories;

use models\User;

/**
 * UserRepository_InMemory
 *
 * @author Volkov Grigorii
 */
class UserRepository_InMemory implements IRepository
{
    public function __construct(
        public array $collection = [],
    ) {}

    public function findBy(string $attribute, mixed $val): User|null
    {
        foreach ($this->collection as $user) {
            // TODO: переделать на сравнение без учета регистра букв
            if ($user->getAttribute($attribute) === $val) {
                return $user;
            }
        }
        return null;
    }

    public function save(User $entity): void
    {
        foreach ($this->collection as $i => $user) {
            if ($entity->is($user)) {
                $this->collection[$i] = $entity;
                return;
            }
        }
        $this->collection[] = $entity;
    }
}
