<?php
namespace tests\validators;

use validators\LengthConstraint;
use tests\TestCase;

/**
 * LengthConstraintTest
 *
 * @author Volkov Grigorii
 */
class LengthConstraintTest extends TestCase
{
    public $minErrorMsg = 'MIN_ERROR';
    public $maxErrorMsg = 'MAX_ERROR';

    public function emptyProvider(): array
    {
        return [
            [null],
            [''],
            [[]],
            ['1'],
            [[1]],
        ];
    }

    /**
     * @return void
     * @dataProvider emptyProvider
     */
    public function testValidateEmpty($value): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue($value));
        $validator = new LengthConstraint();

        $this->assertTrue($validator->validate($stub, 'attr'));
    }

    public function contentsProvider(): array
    {
        return [
            ['', true],
            [[], true],
            [array_fill(0, 2, 1), $this->minErrorMsg],
            [array_fill(0, 4, 1), true],
            [array_fill(0, 10, 1), $this->maxErrorMsg],
            [str_repeat('1', 2), $this->minErrorMsg],
            [str_repeat('1', 4), true],
            [str_repeat('1', 10), $this->maxErrorMsg],
        ];
    }

    /**
     * @return void
     * @dataProvider contentsProvider
     */
    public function testValidateMinMax($value, $expected): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue($value));
        $validator = new LengthConstraint(min: 4, minErrorMessage: $this->minErrorMsg, max: 8, maxErrorMessage: $this->maxErrorMsg);

        $this->assertEquals($expected, $validator->validate($stub, 'attr'));
    }
}
