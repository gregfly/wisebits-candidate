<?php
namespace unit\data\ar;

use db\IDatabase;

/**
 * ActiveRecord
 *
 * @author Volkov Grigorii
 */
abstract class ActiveRecord extends \db\ActiveRecord
{
    public static $db;

    public static function getDb(): IDatabase
    {
        return self::$db;
    }
}
