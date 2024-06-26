<?php
namespace models;

/**
 * User
 *
 * @author Volkov Grigorii
 */
class User extends EntityModel
{
    public function __construct()
    {
        parent::__construct(['id', 'name', 'email', 'created', 'deleted', 'notes']);
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

//    public function softDelete(): bool
//    {
//        if ($this->getAttribute('deleted')) {
//            $this->addError('deleted', 'Пользователь удален');
//            return false;
//        }
//        $this->setAttribute('deleted', date('Y-m-d H:i:s'));
//        return $this->save();
//    }
//
//    protected function afterSave(bool $insert): void
//    {
//        if (!$insert) {
//            Glob::info('Обновление User #' . $this->getPrimaryKey() . ' (' . Json::encode($this->getAttributes(['name', 'email', 'created', 'deleted', 'notes'])) . ')');
//        }
//    }
}
