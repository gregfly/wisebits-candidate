<?php
namespace unit\db;

use unit\TestCase;
use unit\data\ar\ActiveRecord;
use unit\data\models\User;

/**
 * ActiveRecord
 *
 * @author Volkov Grigorii
 */
class ActiveRecordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();
        ActiveRecord::$db = $this->getDatabase();
    }

    public function testModelCreate(): void
    {
        $model = new User();
        $model->setAttributes([
            'name' => 'username',
            'email' => 'email@email.com',
            'created' => '2024-06-19 00:00:00',
        ]);

        $this->assertTrue($model->isNewRecord());
        $this->assertFalse(User::exists('id=1'));
        $this->assertNull($model->getPrimaryKey());
        $this->assertTrue($model->insert());
        $this->assertNotNull($model->getPrimaryKey());
        $this->assertFalse($model->isNewRecord());
        $this->assertEquals('username', $model->getAttribute('name'));
        $this->assertEquals('email@email.com', $model->getAttribute('email'));
        $this->assertEquals('2024-06-19 00:00:00', $model->getAttribute('created'));
        $this->assertTrue(User::exists('id=1'));
    }

    public function testModelUpdate(): void
    {
        $model = new User();
        $model->setAttributes([
            'name' => 'username',
            'email' => 'email@email.com',
            'created' => '2024-06-19 00:00:00',
        ]);
        $this->assertTrue($model->insert());
        $this->assertFalse($model->isNewRecord());

        $model->setAttribute('name', 'newusername');
        $this->assertTrue($model->update());
        $this->assertEquals('newusername', $model->getAttribute('name'));
        $this->assertEquals('email@email.com', $model->getAttribute('email'));
        $this->assertEquals('2024-06-19 00:00:00', $model->getAttribute('created'));

        $model = User::findOne(1);
        $this->assertNotNull($model);
        $this->assertEquals('newusername', $model->getAttribute('name'));
        $model->setAttribute('email', 'email@mail.ru');
        $this->assertTrue($model->update());
    }
}
