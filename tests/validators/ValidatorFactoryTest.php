<?php
namespace tests\validators;

use tests\TestCase;
use validators\IValidator;
use validators\ValidatorFactory;

/**
 * ValidatorFactoryTest
 *
 * @author Volkov Grigorii
 */
class ValidatorFactoryTest extends TestCase
{
    public function testCreateValidator(): void
    {
        $validator = new ValidatorFactory(Validator::class);

        $validator1 = $validator->createValidator();
        $this->assertInstanceOf(Validator::class, $validator1);

        $validator2 = $validator->createValidator();
        $this->assertInstanceOf(Validator::class, $validator2);

        $this->assertTrue($validator1 !== $validator2);
    }
}

final class Validator implements IValidator
{
    public function addContraint(string $attribute, \validators\Constraint $v): static {}
    public function addError(string $name, string $message): void {}
    public function clearErrors(): void {}
    public function getErrors(?string $name = null): array {}
    public function hasErrors(): bool {}
    public function validate(\models\IModel $model): bool {}
    public function addBlacklist(string $attribute, array $words, string $errorMessage = ''): static {}
    public function addCompare(string $attribute, string $operator, string $compareAttribute, string $errorMessage = ''): static {}
    public function addDateTime(string $attribute, string $errorMessage = ''): static {}
    public function addEmail(string $attribute, string $errorMessage = ''): static {}
    public function addLength(string $attribute, ?int $min = null, string $minErrorMessage = '', ?int $max = null, string $maxErrorMessage = ''): static {}
    public function addRegEx(string $attribute, string $pattern, string $errorMessage = ''): static {}
    public function addRequired(string $attribute, string $errorMessage = ''): static {}
    public function addUnique(string $attribute, \repositories\IRepository $repository, string $errorMessage = ''): static {}
}