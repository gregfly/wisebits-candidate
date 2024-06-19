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
        ActiveRecord $model,
        public string $attribute,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        $value = $this->getModelValue();
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
            $this->addModelError($this->errorMessage);
            return false;
        }
        return true;
    }
}
