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
    public $errorMsg = 'ERROR';

    public function testValidateSuccess(): void
    {
        $repositoryStub = $this->createStub('repositories\IRepository');
        $repositoryStub->method('findBy')->will($this->returnValue(null));
        $stub = UserStub::createFrom(1, 'username');
        $validator = new UniqueConstraint($repositoryStub, $this->errorMsg);

        $this->assertTrue($validator->validate($stub, 'username'));
    }

    public function testValidateEditSuccess(): void
    {
        $repositoryStub = $this->createStub('repositories\IRepository');
        $repositoryStub->method('findBy')->will($this->returnValue(UserStub::createFrom(1, 'username')));
        $stub = UserStub::createFrom(1, 'username');
        $validator = new UniqueConstraint($repositoryStub, $this->errorMsg);

        $this->assertTrue($validator->validate($stub, 'username'));
    }

    public function testValidateFailed(): void
    {
        $repositoryStub = $this->createStub('repositories\IRepository');
        $repositoryStub->method('findBy')->will($this->returnValue(UserStub::createFrom(2, 'username')));
        $stub = UserStub::createFrom(1, 'username');
        $validator = new UniqueConstraint($repositoryStub, $this->errorMsg);

        $this->assertEquals($this->errorMsg, $validator->validate($stub, 'username'));
    }
}

class UserStub extends \models\EntityModel
{
    public function __construct()
    {
        parent::__construct(['id', 'username']);
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public static function createFrom(int $id, string $username): static
    {
        $m = new static();
        $m->setAttributes(['id' => $id, 'username' => $username]);
        return $m;
    }
}
