<?php
namespace tests\validators;

use tests\TestCase;
use validators\Validator;

/**
 * ValidatorTest
 *
 * @author Volkov Grigorii
 */
class ValidatorTest extends TestCase
{
    public function testInitState(): void
    {
        $validator = new Validator();

        $this->assertFalse($validator->hasErrors());
        $this->assertEquals([], $validator->getErrors());
    }

    public function testAddClearError(): void
    {
        $validator = new Validator();

        $validator->addError('id', 'ERROR');

        $this->assertTrue($validator->hasErrors());
        $this->assertEquals(['id' => ['ERROR']], $validator->getErrors());

        $validator->clearErrors();

        $this->assertFalse($validator->hasErrors());
        $this->assertEquals([], $validator->getErrors());
    }

    public function testValidateModel(): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue(null));
        $validator = new Validator();

        $this->assertTrue($validator->validate($stub));
        $this->assertFalse($validator->hasErrors());
        $this->assertEquals([], $validator->getErrors());
    }

    public function testValidateModelWithConstraintFailed(): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue(null));
        $mock = $this->createMock('validators\Constraint');
        $mock->expects($this->once())->method('validate')->will($this->returnValue('ERROR'));
        $validator = new Validator();
        $validator->addContraint('id', $mock);

        $this->assertFalse($validator->validate($stub));
        $this->assertTrue($validator->hasErrors());
        $this->assertEquals(['id' => ['ERROR']], $validator->getErrors());
    }

    public function testValidateModelWithConstraintSuccess(): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue(null));
        $mock = $this->createMock('validators\Constraint');
        $mock->expects($this->once())->method('validate')->will($this->returnValue(true));
        $validator = new Validator();
        $validator->addContraint('id', $mock);

        $this->assertTrue($validator->validate($stub));
        $this->assertFalse($validator->hasErrors());
        $this->assertEquals([], $validator->getErrors());
    }
}