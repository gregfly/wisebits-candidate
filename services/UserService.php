<?php
namespace services;

use repositories\IRepository;
use loggers\ILogger;
use models\User;
use validators\Validator;
use validators\RegExConstraint;
use validators\LengthConstraint;
use validators\RequiredConstraint;
use validators\CompareConstraint;
use validators\BlacklistConstraint;
use validators\UniqueConstraint;
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
        public IRepository $repository,
        public ILogger $logger,
    ) {}

    public function create(string $name, string $email, ?string $notes): User
    {
        $model = new User();
        $validator = $this->getValidator('create');
        $model->setAttributes([
            'name' => $name,
            'email' => $email,
            'notes' => $notes,
            'created' => date('Y-m-d H:i:s'),
        ]);
        if (!$validator->validate($model)) {
            throw new ValidationException($validator->getErrors());
        }
        $this->repository->save($model);
        return $model;
    }

    public function update(int $id, array $attributes): User
    {
        $model = $this->findById($id);
        $validator = $this->getValidator('update');
        $model->setAttributes($attributes);
        if (!$validator->validate($model)) {
            throw new ValidationException($validator->getErrors());
        }
        $this->repository->save($model);
        return $model;
    }

    public function softDelete(int $id): User
    {
        $model = $this->findById($id);
        if (!$model->softDelete()) {
            throw new UserNotFoundException('Пользователь ' . $id . ' удален');
        }
        $validator = $this->getValidator('delete');
        if (!$validator->validate($model)) {
            throw new ValidationException($validator->getErrors());
        }
        $this->repository->save($model);
        return $model;
    }

    protected function getValidator(string $scenario): Validator
    {
        return (new Validator())
                //name
                ->addContraint('name', new RequiredConstraint('не может быть пустым'))
                ->addContraint('name', new RegExConstraint(RegExConstraint::PATTERN_LETTER_OR_NUMBER, 'может состоять только из символов a-z и 0-9'))
                ->addContraint('name', new LengthConstraint(8, 'не может быть короче 8 символов', 64, 'не может быть длиннее 64 символов'))
                ->addContraint('name', new BlacklistConstraint(Words::forbiddenWords(), 'не должно содержать слов из списка запрещенных слов'))
                ->addContraint('name', new UniqueConstraint($this->repository, 'должно быть уникальным'))
                //email
                ->addContraint('email', new RequiredConstraint('не может быть пустым'))
                ->addContraint('email', new RegExConstraint(RegExConstraint::PATTERN_EMAIL, 'должно иметь корректный для e-mail адреса формат'))
                ->addContraint('email', new LengthConstraint(max: 256, maxErrorMessage: 'не может быть длиннее 256 символов'))
                ->addContraint('email', new BlacklistConstraint(Words::forbiddenDomains(), 'не должно принадлежать домену из списка "ненадежных" доменов'))
                ->addContraint('email', new UniqueConstraint($this->repository, 'должно быть уникальным'))
                //created
                ->addContraint('created', new RequiredConstraint('не может быть пустым'))
                ->addContraint('created', new RegExConstraint(RegExConstraint::PATTERN_DATETIME, 'должно иметь корректный формат датавремя'))
                //deleted
                ->addContraint('deleted', new CompareConstraint('>=', 'created', 'не может быть меньше значения поля created'))
                ->addContraint('deleted', new RegExConstraint(RegExConstraint::PATTERN_DATETIME, 'должно иметь корректный формат датавремя'));
    }

    protected function &findById($id): User
    {
        $model = $this->repository->findBy(User::primaryKey(), $id);
        if (!$model) {
            throw new UserNotFoundException('Пользователь ' . $id . ' не найден');
        }
        return $model;
    }
}
