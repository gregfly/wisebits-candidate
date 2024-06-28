<?php
namespace tests\services;

use services\UserService;
use models\User;
use exceptions\UserNotFoundException;
use exceptions\ValidationException;
use tests\TestCase;
use repositories\IRepository;
use loggers\ILogger;
use validators\IValidatorFactory;
use validators\ValidatorFactory;
use validators\Validator;

/**
 * UserServiceTest
 *
 * @author Volkov Grigorii
 */
class UserServiceTest extends TestCase
{
    protected function mockRepository(): IRepository
    {
        return $this->createMock('repositories\IRepository');
    }

    protected function mockLogger(): ILogger
    {
        return $this->createMock('loggers\ILogger');
    }

    protected function mockValidatorFactory(): IValidatorFactory
    {
        return new ValidatorFactory(Validator::class);
    }

    public function invalidInputsProvider(): array
    {
        return [
            'short username' => [str_repeat('u', 7), 'admin@mail.ru', null],
            'long username' => [str_repeat('u', 65), 'admin@mail.ru', null],
            'invalid letters in username' => ['admin000&', 'admin@mail.ru', null],
            'invalid email' => ['username', 'admin', null],
            'long email' => ['username', str_repeat('e', 257 - 8) . '@mail.ru', null],
            'forbidden word in username' => ['password1', 'admin@mail.ru', null],
            'forbidden domain in email' => ['username', 'admin@dom2.ru', null],
        ];
    }

    /**
     * @return void
     * @dataProvider invalidInputsProvider
     */
    public function testCreateUserFailed($username, $email, $notes): void
    {
        $mock = $this->mockRepository();
        $userService = new UserService($mock, $this->mockLogger(), $this->mockValidatorFactory());

        $this->expectException(ValidationException::class);
        $userService->create($username, $email, $notes);
    }

    public function validInputsProvider(): array
    {
        return [
            'typical' => ['username', 'admin@mail.ru', null],
            'letter and numbers' => ['user0001', 'admin11@mail.ru', null],
            'long username' => [str_repeat('u', 64), 'admin@mail.ru', null],
            'long email' => ['username', str_repeat('e', 256 - 8) . '@mail.ru', null],
            'with notes' => ['username', 'admin@mail.ru', 'notes'],
        ];
    }

    /**
     * @return void
     * @dataProvider validInputsProvider
     */
    public function testCreateUserSuccess($username, $email, $notes): void
    {
        $mock = $this->mockRepository();
        $userService = new UserService($mock, $this->mockLogger(), $this->mockValidatorFactory());

        $user = $userService->create($username, $email, $notes);
        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isDeleted());
        $this->assertNotNull($user->getAttribute('created'));
    }

    public function testUpdateUserNotFound(): void
    {
        $mock = $this->mockRepository();
        $userService = new UserService($mock, $this->mockLogger(), $this->mockValidatorFactory());

        $this->expectException(UserNotFoundException::class);
        $userService->update(1, ['email' => 'admin@mail.ru']);
    }

    /**
     * @return void
     * @dataProvider invalidInputsProvider
     */
    public function testUpdateUserFailed($username, $email, $notes): void
    {
        $user = new User();
        $user->setAttributes(['id' => 1, 'name' => 'user0001', 'email' => 'admin@mail.ru', 'created' => '2024-06-26 00:00:00']);
        $mock = $this->mockRepository();
        $mock->method('findBy')->will($this->returnValue($user));
        $userService = new UserService($mock, $this->mockLogger(), $this->mockValidatorFactory());

        $this->expectException(ValidationException::class);
        $userService->update(1, ['name' => $username, 'email' => $email, 'notes' => $notes]);
    }

    /**
     * @return void
     * @dataProvider validInputsProvider
     */
    public function testUpdateUserSuccess($username, $email, $notes): void
    {
        $user = new User();
        $user->setAttributes(['id' => 1, 'name' => 'user0001', 'email' => 'admin@mail.ru', 'created' => '2024-06-26 00:00:00']);
        $repositoryMock = $this->mockRepository();
        $repositoryMock->method('findBy')->will($this->returnValue($user));
        $loggerMock = $this->mockLogger();
        $loggerMock->expects($this->once())->method('log');
        $userService = new UserService($repositoryMock, $loggerMock, $this->mockValidatorFactory());

        $user = $userService->update(1, ['name' => $username, 'email' => $email, 'notes' => $notes]);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testSoftDeleteUserNotFound(): void
    {
        $mock = $this->mockRepository();
        $userService = new UserService($mock, $this->mockLogger(), $this->mockValidatorFactory());

        $this->expectException(UserNotFoundException::class);
        $userService->softDelete(1);
    }

    public function testSoftDeleteUserAlreadyDeleted(): void
    {
        $user = new User();
        $user->setAttributes(['id' => 1, 'name' => 'user0001', 'email' => 'admin@mail.ru', 'created' => '2024-06-26 00:00:00', 'deleted' => '2024-06-26 00:00:00']);
        $mock = $this->mockRepository();
        $mock->method('findBy')->will($this->returnValue($user));
        $userService = new UserService($mock, $this->mockLogger(), $this->mockValidatorFactory());

        $this->expectException(UserNotFoundException::class);
        $userService->softDelete(1);
    }

    public function testSoftDeleteUserSuccess(): void
    {
        $user = new User();
        $user->setAttributes(['id' => 1, 'name' => 'user0001', 'email' => 'admin@mail.ru', 'created' => '2024-06-26 00:00:00']);
        $repositoryMock = $this->mockRepository();
        $repositoryMock->method('findBy')->will($this->returnValue($user));
        $loggerMock = $this->mockLogger();
        $loggerMock->expects($this->once())->method('log');
        $userService = new UserService($repositoryMock, $loggerMock, $this->mockValidatorFactory());

        $user = $userService->softDelete(1);
        $this->assertTrue($user->isDeleted());
    }
}
