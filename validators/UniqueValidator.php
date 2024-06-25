<?php
namespace validators;

use db\ActiveRecord;

/**
 * UniqueValidator
 *
 * @author Volkov Grigorii
 */
class UniqueValidator extends Validator
{
    public function __construct(
        public string $errorMessage,
    ) {
        parent::__construct();
    }

    public function validate(IModel $model, string $attribute): bool
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
            $model->addError($attribute, $this->errorMessage);
            return false;
        }
        return true;
    }
}
