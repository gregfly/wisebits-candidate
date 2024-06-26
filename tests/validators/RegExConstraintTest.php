<?php
namespace tests\validators;

use validators\RegExConstraint;
use tests\TestCase;

/**
 * RegExConstraintTest
 *
 * @author Volkov Grigorii
 */
class RegExConstraintTest extends TestCase
{
    public $errorMsg = 'ERROR';

    public function emptyProvider(): array
    {
        return [
            [null],
            [''],
            [[]],
            ['1'],
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
        $validator = new RegExConstraint(RegExConstraint::PATTERN_LETTER_OR_NUMBER, $this->errorMsg);

        $this->assertTrue($validator->validate($stub, 'attr'));
    }

    public function attrsProvider(): array
    {
        return [
            ['gregfly', true],
            ['greg---fly', $this->errorMsg],
            ['GREGFLY', true],
            ['greg fly', $this->errorMsg],
            ['gregfly999', true],
        ];
    }

    /**
     * @return void
     * @dataProvider attrsProvider
     */
    public function testValidateAttrValue($value, $expected): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue($value));
        $validator = new RegExConstraint(RegExConstraint::PATTERN_LETTER_OR_NUMBER, $this->errorMsg);

        $this->assertEquals($expected, $validator->validate($stub, 'attr'));
    }
}
