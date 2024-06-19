<?php
namespace unit\models;

use unit\TestCase;
use models\BaseModel;
use validators\Validator;
use exceptions\InvalidAttributeException;

/**
 * ActiveRecord
 *
 * @author Volkov Grigorii
 */
class BaseModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testGetAttributes(): void
    {
        $model = new TestBaseModel();
        $this->assertEquals(['id', 'name'], $model->attributeNames());
        $this->assertNull($model->getAttribute('id'));
        $this->assertNull($model->getAttribute('name'));
        $this->expectException(InvalidAttributeException::class);
        $model->getAttribute('unknown');
    }

    public function testSetAttributes(): void
    {
        $model = new TestBaseModel();
        $model->setAttribute('id', 1);
        $this->assertEquals(1, $model->getAttribute('id'));
        $model->setAttribute('name', '2');
        $this->assertEquals('2', $model->getAttribute('name'));
        $this->expectException(InvalidAttributeException::class);
        $model->setAttribute('unknown', 1);
    }

    public function testValidation(): void
    {
        $model = new TestBaseModel();
        $model->switchValidator(false);
        $this->assertFalse($model->validate());
        $this->assertTrue($model->hasErrors());
        $this->assertNotEmpty($model->getErrors());
        $this->assertEmpty($model->getErrors('id'));
        $this->assertNotEmpty($model->getErrors('name'));
        $this->assertContains('error_message', $model->getErrors('name'));
        $model->switchValidator(true);
        $this->assertTrue($model->validate());
        $this->assertEmpty($model->getErrors());
    }
}

class TestValidator extends Validator
{
    public function __construct(
        public \models\IModel $model,
        public string $attribute,
        public bool $switcher,
        public string $errorMessage,
    ) {
        parent::__construct($model, $attribute);
    }

    public function validate(): bool
    {
        if (!$this->switcher) {
            $this->addModelError($this->errorMessage);
        }
        return $this->switcher;
    }
}

class TestBaseModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(['id', 'name']);
    }

    private $switcher = true;

    public function switchValidator(bool $v): void
    {
        $this->switcher = $v;
    }

    public function getValidators(): \Generator
    {
        yield new TestValidator($this, 'name', $this->switcher, 'error_message');
    }
}