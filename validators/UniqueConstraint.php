<?php
namespace validators;

use repositories\IRepository;
use repositories\IEntity;
use models\IModel;
use exceptions\InvalidArgumentException;

/**
 * UniqueConstraint
 *
 * @author Volkov Grigorii
 */
class UniqueConstraint extends Constraint
{
    public function __construct(
        public IRepository $repository,
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): true|string
    {
        if (!($model instanceof IEntity)) {
            throw new InvalidArgumentException('$model должен реализовать интерфейс IEntity');
        }
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $entity = $this->repository->findBy($attribute, $value);
        if ($entity && !$model->is($entity)) {
            return $this->errorMessage;
        }
        return true;
    }
}