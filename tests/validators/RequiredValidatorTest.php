<?php
namespace unit\validators;

use validators\RequiredValidator;
use unit\data\models\FakedValidationModel;
use unit\TestCase;

/**
 * RequiredValidatorTest
 *
 * @author Volkov Grigorii
 */
class RequiredValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testValidateAttr(): void
    {
        $model = FakedValidationModel::createWithAttributes(['attr']);
        $validator = new RequiredValidator($model, 'attr', '');

        $model->setAttribute('attr', null);
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', '');
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', []);
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', '123');
        $this->assertTrue($validator->validate());
    }
}
