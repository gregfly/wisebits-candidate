<?php
namespace tests\validators;

use validators\CompareConstraint;
use tests\TestCase;

/**
 * CompareConstraintTest
 *
 * @author Volkov Grigorii
 */
class CompareConstraintTest extends TestCase
{
    public $errorMsg = 'ERROR';

    public function testValidateAttr(): void
    {
        $value = 88239;
        foreach ($this->getOperationTestData($value) as $op => $tests) {
            foreach ($tests as $test) {
                $validator = new CompareConstraint($op, 'attr_cmp', $this->errorMsg);
                $stub = new class($value, $test[0]) implements \models\IModel {
                    public function __construct(
                        public mixed $attr,
                        public mixed $attr_cmp,
                    ) {}
                    public function getAttribute(string $name): mixed {
                        return $this->$name;
                    }
                    public function setAttribute(string $name, mixed $value): void {}
                };

                $this->assertEquals($test[1], $validator->validate($stub, 'attr'), "Testing $op");
            }
        }
    }

    protected function getOperationTestData($value)
    {
        return [
            '===' => [
                [$value, true],
                [(string) $value, $this->errorMsg],
                [(float) $value, $this->errorMsg],
                [$value + 1, $this->errorMsg],
            ],
            '==' => [
                [$value, true],
                [(string) $value, true],
                [(float) $value, true],
                [$value + 1, $this->errorMsg],
            ],
            '!=' => [
                [$value, $this->errorMsg],
                [(string) $value, $this->errorMsg],
                [(float) $value, $this->errorMsg],
                [$value + 0.00001, true],
                [false, true],
            ],
            '!==' => [
                [$value, $this->errorMsg],
                [(string) $value, true],
                [(float) $value, true],
                [false, true],
            ],
            '<' => [
                [$value, $this->errorMsg],
                [$value + 1, true],
                [$value - 1, $this->errorMsg],
            ],
            '<=' => [
                [$value, true],
                [$value + 1, true],
                [$value - 1, $this->errorMsg],
            ],
            '>' => [
                [$value, $this->errorMsg],
                [$value + 1, $this->errorMsg],
                [$value - 1, true],
            ],
            '>=' => [
                [$value, true],
                [$value + 1, $this->errorMsg],
                [$value - 1, true],
            ],
        ];
    }
}
