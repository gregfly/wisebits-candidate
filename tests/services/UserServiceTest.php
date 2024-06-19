<?php
namespace unit\services;

use services\UserService;
use exceptions\UserNotFoundException;
use unit\TestCase;

/**
 * UserServiceTest
 *
 * @author Volkov Grigorii
 */
class UserServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testCreateUser(): void
    {
        $userService = new UserService();

        $user = $userService->create('admin', 'admin@mail.ru', null);
        $this->assertTrue($user->hasErrors());
        $this->assertNull($user->getAttribute('id'));

        $user = $userService->create('admin', 'admin@mail.com', null);
        $this->assertTrue($user->hasErrors());

        $user = $userService->create('root', 'admin@mail.ru', null);
        $this->assertTrue($user->hasErrors());

        $user = $userService->create('adminadmin', 'admin@mail.ru', null);
        $this->assertFalse($user->hasErrors());
        $this->assertNotNull($user->getAttribute('id'));
        $this->assertNotNull($user->getAttribute('created'));
        $this->assertNull($user->getAttribute('deleted'));
    }

    public function testUpdateNoUser(): void
    {
        $userService = new UserService();

        $this->expectException(UserNotFoundException::class);
        $userService->update(1, ['email' => 'admin@mail.ru']);
    }

    public function testUpdateUser(): void
    {
        $userService = new UserService();
        $origUser = $userService->create('adminadmin', 'admin@mail.ru', null);

        $user = $userService->update($origUser->getPrimaryKey(), ['email' => 'invalid']);
        $this->assertTrue($user->hasErrors());

        $user = $userService->update($origUser->getPrimaryKey(), ['email' => 'admin@mail.com']);
        $this->assertEquals('admin@mail.com', $user->getAttribute('email'));
    }

    public function testDeleteNoUser(): void
    {
        $userService = new UserService();

        $this->expectException(UserNotFoundException::class);
        $userService->softDelete(1);
    }

    public function testSoftDeleteUser(): void
    {
        $userService = new UserService();
        $origUser = $userService->create('adminadmin', 'admin@mail.ru', null);
        $this->assertNull($origUser->getAttribute('deleted'));

        $user = $userService->softDelete($origUser->getPrimaryKey());
        $this->assertFalse($user->hasErrors());
        $this->assertNotNull($user->getAttribute('deleted'));
    }
}
