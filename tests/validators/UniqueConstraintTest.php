<?php
namespace tests\validators;

use validators\UniqueConstraint;
use tests\TestCase;

/**
 * UniqueConstraintTest
 *
 * @author Volkov Grigorii
 */
class UniqueConstraintTest extends TestCase
{
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
        $validator = new UniqueConstraint($model, 'name', '');

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
