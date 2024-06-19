<?php
namespace services;

use models\User;
use exceptions\UserNotFoundException;

/**
 * UserService
 *
 * @author Volkov Grigorii
 */
class UserService
{
    public function create(string $name, string $email, ?string $notes): User
    {
        $model = new User();
        $model->setAttributes([
            'name' => $name,
            'email' => $email,
            'notes' => $notes,
            'created' => $this->now(),
        ]);
        $model->save();
        return $model;
    }

    public function update(int $id, array $attributes): User
    {
        $model = $this->findById($id);
        $model->setAttributes($attributes);
        $model->save();
        return $model;
    }

    public function softDelete(int $id): User
    {
        $model = $this->findById($id);
        $model->setAttribute('deleted', $this->now());
        $model->save();
        return $model;
    }

    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    protected function &findById($id): User
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new UserNotFoundException('Пользователь ' . $id . ' не найден');
        }
        return $model;
    }
}
