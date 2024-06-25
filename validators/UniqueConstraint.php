<?php
namespace validators;

use db\ActiveRecord;

/**
 * UniqueConstraint
 *
 * @author Volkov Grigorii
 */
class UniqueConstraint extends Constraint
{
    public function __construct(
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): true|string
    {
        $value = $model->getAttribute($attribute);
        if ($this->isEmpty($value)) {
            return true;
        }
        $modelClass = $this->model::class;
        $condition = $this->attribute . '=:val';
        $params = [':val' => $value];
        if (!$this->model->isNewRecord()) {
            $condition .= ' AND ' . $modelClass::primaryKey() . '!=:id';
            $params[':id'] = $this->model->getPrimaryKey();
        }
        if ($modelClass::exists($condition, $params)) {
            return $this->errorMessage;
        }
        return true;
    }
}
