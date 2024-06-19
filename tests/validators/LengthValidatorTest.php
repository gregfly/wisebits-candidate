<?php
namespace unit\validators;

use validators\LengthValidator;
use unit\data\models\FakedValidationModel;
use unit\TestCase;

/**
 * LengthValidatorTest
 *
 * @author Volkov Grigorii
 */
class LengthValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testValidateEmpty(): void
    {
        $model = FakedValidationModel::createWithAttributes(['attr']);
        $validator = new LengthValidator($model, 'attr');
        $model->setAttribute('attr', null);
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', '');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', []);
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', '1');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', [1]);
        $this->assertTrue($validator->validate());
    }

    public function testValidateMinMax(): void
    {
        $model = FakedValidationModel::createWithAttributes(['attr']);
        $validator = new LengthValidator($model, 'attr', min: 4, max: 8);

        $model->setAttribute('attr', '');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', []);
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', array_fill(0, 2, 1));
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', array_fill(0, 4, 1));
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', array_fill(0, 10, 1));
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', str_repeat('1', 2));
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', str_repeat('1', 4));
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', str_repeat('1', 10));
        $this->assertFalse($validator->validate());
    }
}
