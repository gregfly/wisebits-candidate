<?php
namespace tests\validators;

use validators\RequiredConstraint;
use tests\TestCase;

/**
 * RequiredConstraintTest
 *
 * @author Volkov Grigorii
 */
class RequiredConstraintTest extends TestCase
{
    public $errorMsg = 'ERROR';

    public function valuesProvider(): array
    {
        return [
            [null, $this->errorMsg],
            ['', $this->errorMsg],
            [[], $this->errorMsg],
            ['1', true],
            [[1], true],
        ];
    }

    /**
     * @return void
     * @dataProvider valuesProvider
     */
    public function testValidateAttr($value, $expected): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue($value));
        $validator = new RequiredConstraint($this->errorMsg);

        $this->assertEquals($expected, $validator->validate($stub, 'attr'));
    }
}
