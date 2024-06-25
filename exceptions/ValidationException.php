<?php
namespace exceptions;

/**
 * ValidationException
 *
 * @author Volkov Grigorii
 */
class ValidationException extends \Exception
{
    public function __construct(public $validationErrors): \Exception
    {
        return parent::__construct(implode(' ', $this->validationErrors), 400, null);
    }
}
