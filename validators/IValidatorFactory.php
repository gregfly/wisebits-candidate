<?php
namespace validators;

/**
 * IValidatorFactory
 *
 * @author Volkov Grigorii
 */
interface IValidatorFactory
{
    public function createValidator(): IValidator;
}
