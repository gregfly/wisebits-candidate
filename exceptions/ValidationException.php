<?php
namespace exceptions;

use helpers\Json;

/**
 * ValidationException
 *
 * @author Volkov Grigorii
 */
class ValidationException extends \Exception
{
    public function __construct(public $validationErrors)
    {
        return parent::__construct(Json::encode($this->validationErrors), 400, null);
    }
}
