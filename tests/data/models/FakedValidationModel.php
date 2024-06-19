<?php
namespace unit\data\models;

/**
 * FakedValidationModel
 *
 * @author Volkov Grigorii
 */
class FakedValidationModel extends \models\BaseModel
{
    public static function createWithAttributes(array $attrs): static
    {
        return new static($attrs);
    }
}
