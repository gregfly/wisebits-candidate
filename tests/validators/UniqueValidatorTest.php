<?php
namespace unit\validators;

use validators\UniqueValidator;
use unit\data\models\User;
use unit\data\ar\ActiveRecord;
use unit\TestCase;

/**
 * RegExValidatorTest
 *
 * @author Volkov Grigorii
 */
class UniqueValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();
        ActiveRecord::$db = $this->getDatabase();
    }

    public function testValidateUnique(): void
    {
        $newUser = new User();
        $newUser->setAttributes([
            'name' => 'username',
            'email' => 'username@mail.ru',
            'created' => date('Y-m-d H:i:s'),
        ]);
        $newUser->insert();

        $model = new User();
        $validator = new UniqueValidator($model, 'name', '');

        $model->setAttribute('name', '');
        $this->assertTrue($validator->validate());

        $model->setAttribute('name', 'username1');
        $this->assertTrue($validator->validate());

        $model->setAttribute('name', 'Username');
        $this->assertFalse($validator->validate());

        $model->setAttribute('name', 'username');
        $this->assertFalse($validator->validate());
    }
}
