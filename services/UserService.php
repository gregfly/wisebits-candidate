<?php
namespace services;

use repositories\IRepository;
use loggers\ILogger;
use validators\IValidatorFactory;
use models\User;
use validators\IValidator;
use helpers\Words;
use exceptions\UserNotFoundException;
use exceptions\ValidationException;
use events\UserChangedEvent;

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
        public IValidatorFactory $validatorFactory,
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
        $this->dispatchUpdatedEvent($model);
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
        $this->dispatchUpdatedEvent($model);
        return $model;
    }

    protected function getValidator(string $scenario): IValidator
    {
        return $this->validatorFactory->createValidator()
                //name
                ->addRequired('name', 'не может быть пустым')
                ->addRegEx('name', '#^[a-z0-9]+$#i', 'может состоять только из символов a-z и 0-9')
                ->addLength('name', 8, 'не может быть короче 8 символов', 64, 'не может быть длиннее 64 символов')
                ->addBlacklist('name', Words::forbiddenWords(), 'не должно содержать слов из списка запрещенных слов')
                ->addUnique('name', $this->repository, 'должно быть уникальным')
                //email
                ->addRequired('email', 'не может быть пустым')
                ->addEmail('email', 'должно иметь корректный для e-mail адреса формат')
                ->addLength('email', max: 256, maxErrorMessage: 'не может быть длиннее 256 символов')
                ->addBlacklist('email', Words::forbiddenDomains(), 'не должно принадлежать домену из списка "ненадежных" доменов')
                ->addUnique('email', $this->repository, 'должно быть уникальным')
                //created
                ->addRequired('created', 'не может быть пустым')
                ->addDateTime('created', 'должно иметь корректный формат датавремя')
                //deleted
                ->addCompare('deleted', '>=', 'created', 'не может быть меньше значения поля created')
                ->addDateTime('deleted', 'должно иметь корректный формат датавремя');
    }

    protected function &findById($id): User
    {
        $model = $this->repository->findBy(User::primaryKey(), $id);
        if (!$model) {
            throw new UserNotFoundException('Пользователь ' . $id . ' не найден');
        }
        return $model;
    }

    protected function dispatchUpdatedEvent(User $user): void
    {
        (new UserChangedEvent($user, $this->logger))->dispatch();
    }
}
