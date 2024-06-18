<?php
namespace validators;

use models\ActiveRecord;

/**
 * UniqueValidator
 *
 * @author Volkov Grigorii
 */
class UniqueValidator extends Validator
{
    public function __construct(
        public ActiveRecord $model,
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
            $condition .= ' AND ' . $modelClass::primaryKey() . '!=' . $this->model->getPrimaryKey();
        }
        if ($modelClass::exists($condition, $params)) {
            $this->addModelError($this->errorMessage);
            return false;
        }
        return true;
    }
}
