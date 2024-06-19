<?php
namespace unit\data\models;

/**
 * User
 *
 * @author Volkov Grigorii
 */
class User extends \unit\data\ar\ActiveRecord
{
    public function __construct()
    {
        parent::__construct(['id', 'name', 'email', 'created', 'deleted', 'notes']);
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public static function primaryKey(): string
    {
        return 'id';
    }
}
