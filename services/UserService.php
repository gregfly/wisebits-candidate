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
        $db = User::getDb();
        $db->beginTransaction();
        try {
            $model = new User();
            $model->setAttributes([
                'name' => $name,
                'email' => $email,
                'notes' => $notes,
                'created' => $this->now(),
            ]);
            if ($model->save()) {
                $db->commit();
            } else {
                $db->rollback();
            }
            return $model;
        } catch (\Throwable $th) {
            $db->rollback();
            throw $th;
        }
    }

    public function update(int $id, array $attributes): User
    {
        $db = User::getDb();
        $db->beginTransaction();
        try {
            $model = $this->findById($id);
            $model->setAttributes($attributes);
            if ($model->save()) {
                $db->commit();
            } else {
                $db->rollback();
            }
            return $model;
        } catch (\Throwable $th) {
            $db->rollback();
            throw $th;
        }
    }

    public function softDelete(int $id): User
    {
        $db = User::getDb();
        $db->beginTransaction();
        try {
            $model = $this->findById($id);
            if ($model->softDelete()) {
                $db->commit();
            } else {
                $db->rollback();
            }
            return $model;
        } catch (\Throwable $th) {
            $db->rollback();
            throw $th;
        }
        return $model;
    }

    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    protected function &findById($id): User
    {
        $model = User::findOneForUpdate($id);
        if (!$model) {
            throw new UserNotFoundException('Пользователь ' . $id . ' не найден');
        }
        return $model;
    }
}
