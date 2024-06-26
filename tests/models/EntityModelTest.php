<?php
namespace tests\models;

use tests\TestCase;
use exceptions\InvalidAttributeException;

/**
 * EntityModelTest
 *
 * @author Volkov Grigorii
 */
class EntityModelTest extends TestCase
{
    public function testGetSetAttribute(): void
    {
        $model = new User();

        $this->assertNull($model->getAttribute('id'));
        $this->assertNull($model->getAttribute('username'));

        $model->setAttribute('id', 1);
        $model->setAttribute('username', 'admin');

        $this->assertEquals(1, $model->getAttribute('id'));
        $this->assertEquals('admin', $model->getAttribute('username'));

        try {
            $model->getAttribute('unknown');
        } catch (\Throwable $ex) {
            $this->assertInstanceOf(InvalidAttributeException::class, $ex);
        }
        try {
            $model->setAttribute('unknown', 1);
        } catch (\Throwable $ex) {
            $this->assertInstanceOf(InvalidAttributeException::class, $ex);
        }
    }

    public function testGetSetAttributes(): void
    {
        $model = new User();

        $this->assertEquals(['id' => null, 'username' => null], $model->getAttributes(['id', 'username']));

        $model->setAttributes(['id' => 1, 'username' => 'admin']);
        
        $this->assertEquals(['id' => 1, 'username' => 'admin'], $model->getAttributes(['id', 'username']));

        try {
            $model->getAttributes(['unknown']);
        } catch (\Throwable $ex) {
            $this->assertInstanceOf(InvalidAttributeException::class, $ex);
        }
        try {
            $model->setAttributes(['unknown' => 1]);
        } catch (\Throwable $ex) {
            $this->assertInstanceOf(InvalidAttributeException::class, $ex);
        }
    }

    public function testAttributeNames(): void
    {
        $model = new User();

        $this->assertEquals(['id', 'username'], $model->attributeNames());
    }

    public function testEquals(): void
    {
        $model1 = new User();
        $model2 = new User();

        $this->assertFalse($model1->is($model2));

        $model1->setAttribute('id', 1);
        $model2->setAttribute('id', null);

        $this->assertFalse($model1->is($model2));

        $model1->setAttribute('id', null);
        $model2->setAttribute('id', 1);

        $this->assertFalse($model1->is($model2));

        $model1->setAttribute('id', 1);
        $model2->setAttribute('id', 2);

        $this->assertFalse($model1->is($model2));

        $model1->setAttribute('id', 1);
        $model2->setAttribute('id', 1);

        $this->assertTrue($model1->is($model2));
    }
}

class User extends \models\EntityModel
{
    public function __construct()
    {
        parent::__construct(['id', 'username']);
    }

    public static function primaryKey(): string
    {
        return 'id';
    }
}