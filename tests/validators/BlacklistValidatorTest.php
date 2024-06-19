<?php
namespace unit\validators;

use validators\BlacklistValidator;
use unit\data\models\FakedValidationModel;
use unit\TestCase;

/**
 * BlacklistValidatorTest
 *
 * @author Volkov Grigorii
 */
class BlacklistValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testValidateAttr(): void
    {
        $words = ['word', 'SORT'];
        $model = FakedValidationModel::createWithAttributes(['attr' => 'world']);
        $validator = new BlacklistValidator($model, 'attr', $words, '');

        $model->setAttribute('attr', '');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', 'world');
        $this->assertTrue($validator->validate());
        $model->setAttribute('attr', 'PASSWORD');
        $this->assertFalse($validator->validate());
        $model->setAttribute('attr', 'multisort');
        $this->assertFalse($validator->validate());

        $validator->words = [''];
        $model->setAttribute('attr', 'word');
        $this->assertFalse($validator->validate());
    }
}
