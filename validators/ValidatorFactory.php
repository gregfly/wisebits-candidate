<?php
namespace validators;

/**
 * ValidatorFactory
 *
 * @author Volkov Grigorii
 */
class ValidatorFactory implements IValidatorFactory
{
    public function __construct(
        public string $validatorClassName = Validator::class,
    ) {}

    public function createValidator(): IValidator
    {
        return new $this->validatorClassName();
    }
}
