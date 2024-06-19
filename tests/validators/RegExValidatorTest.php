<?php
namespace unit\validators;

use validators\RegExValidator;
use unit\data\models\FakedValidationModel;
use unit\TestCase;

/**
 * RegExValidatorTest
 *
 * @author Volkov Grigorii
 */
class RegExValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testValidateRegExp(): void
    {
        $model = FakedValidationModel::createWithAttributes(['attr']);
        $validator = new RegExValidator($model, 'attr', RegExValidator::PATTERN_LETTER_OR_NUMBER, '');
        $model->setAttribute('attr', '');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', 'gregfly1');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', '--------');
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', 'user_name');
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', 'userName');
        $this->assertTrue($validator->validate());
    }
}
