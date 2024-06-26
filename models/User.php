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

    public function isDeleted(): bool
    {
        return !empty($this->getAttribute('deleted'));
    }

    public function softDelete(): bool
    {
        if ($this->isDeleted()) {
            return false;
        }
        $this->setAttribute('deleted', date('Y-m-d H:i:s'));
        return true;
    }
}
