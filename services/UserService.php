<?php
namespace services;

use models\User;
use validators\Validator;
use validators\RegExValidator;
use validators\LengthValidator;
use validators\RequiredValidator;
use validators\CompareValidator;
use validators\BlacklistValidator;
use validators\UniqueValidator;
use helpers\Words;
use exceptions\UserNotFoundException;
use exceptions\ValidationException;

/**
 * UserService
 *
 * @author Volkov Grigorii
 */
class UserService
{
    public function __construct(
        public IRepository $repository
    ) {}

    public function create(string $name, string $email, ?string $notes): User
    {
        $repository = $this->repository;
        $validator = $this->createValidator($repository);
        $model = new User();
        $model->setAttributes([
            'name' => $name,
            'email' => $email,
            'notes' => $notes,
            'created' => $this->now(),
        ]);
        if (!$validator->validate($model)) {
            throw new ValidationException($validator->getErrors());
        }
        $repository->storeUser($model);
        return $model;
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

    protected function createValidator(IRepository $repository): Validator
    {
        return (new Validator())
                //name
                ->addContraint('name', new RequiredValidator('не может быть пустым'))
                ->addContraint('name', new RegExValidator(RegExValidator::PATTERN_LETTER_OR_NUMBER, 'может состоять только из символов a-z и 0-9'))
                ->addContraint('name', new LengthValidator(8, 'не может быть короче 8 символов', 64, 'не может быть длиннее 64 символов'))
                ->addContraint('name', new BlacklistValidator(Words::forbiddenWords(), 'не должно содержать слов из списка запрещенных слов'))
                ->addContraint('name', new UniqueValidator('должно быть уникальным'))
                //email
                ->addContraint('email', new RequiredValidator('не может быть пустым'))
                ->addContraint('email', new RegExValidator(RegExValidator::PATTERN_EMAIL, 'должно иметь корректный для e-mail адреса формат'))
                ->addContraint('email', new LengthValidator(max: 256, maxErrorMessage: 'не может быть длиннее 256 символов'))
                ->addContraint('email', new BlacklistValidator($this, 'email', Words::forbiddenDomains(), 'не должно принадлежать домену из списка "ненадежных" доменов'))
                ->addContraint('email', new UniqueValidator($this, 'email', 'должно быть уникальным'))
                //created
                ->addContraint('created', new RequiredValidator('не может быть пустым'))
                ->addContraint('created', new RegExValidator(RegExValidator::PATTERN_DATETIME, 'должно иметь корректный формат датавремя'))
                //deleted
                ->addContraint('deleted', new CompareValidator('>=', 'created', 'не может быть меньше значения поля created'))
                ->addContraint('deleted', new RegExValidator(RegExValidator::PATTERN_DATETIME, 'должно иметь корректный формат датавремя'));
    }

    protected function &findById($id): User
    {
        $model = $this->repository->findUserForUpdate($id);
        if (!$model) {
            throw new UserNotFoundException('Пользователь ' . $id . ' не найден');
        }
        return $model;
    }
}
