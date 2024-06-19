<?php
namespace unit\validators;

use validators\CompareValidator;
use unit\data\models\FakedValidationModel;
use unit\TestCase;

/**
 * CompareValidatorTest
 *
 * @author Volkov Grigorii
 */
class CompareValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testValidateAttr(): void
    {
        $value = 88239;
        $model = FakedValidationModel::createWithAttributes(['attr' => $value, 'attr_cmp']);
        foreach ($this->getOperationTestData($value) as $op => $tests) {
            $validator = new CompareValidator($model, 'attr', $op, 'attr_cmp', '');
            foreach ($tests as $test) {
                $model->setAttribute('attr_cmp', $test[0]);
                $this->assertEquals($test[1], $validator->validate(), "Testing $op");
            }
        }
    }

    protected function getOperationTestData($value)
    {
        return [
            '===' => [
                [$value, true],
                [(string) $value, false],
                [(float) $value, false],
                [$value + 1, false],
            ],
            '==' => [
                [$value, true],
                [(string) $value, true],
                [(float) $value, true],
                [$value + 1, false],
            ],
            '!=' => [
                [$value, false],
                [(string) $value, false],
                [(float) $value, false],
                [$value + 0.00001, true],
                [false, true],
            ],
            '!==' => [
                [$value, false],
                [(string) $value, true],
                [(float) $value, true],
                [false, true],
            ],
            '<' => [
                [$value, false],
                [$value + 1, true],
                [$value - 1, false],
            ],
            '<=' => [
                [$value, true],
                [$value + 1, true],
                [$value - 1, false],
            ],
            '>' => [
                [$value, false],
                [$value + 1, false],
                [$value - 1, true],
            ],
            '>=' => [
                [$value, true],
                [$value + 1, false],
                [$value - 1, true],
            ],
        ];
    }
}
