<?php
namespace unit\db;

use unit\TestCase;
use db\PdoDatabase;
use exceptions\DatabaseException;

/**
 * PdoDatabaseTest
 *
 * @author Volkov Grigorii
 */
class PdoDatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->destroyApplication();
    }

    public function testOpenClose(): void
    {
        $db = $this->prepareDb(false);

        $this->assertFalse($db->isActive());
        $db->open();
        $this->assertTrue($db->isActive());
        $db->close();
        $this->assertFalse($db->isActive());
        $db->open();
        $db->beginTransaction();
        $this->assertTrue($db->isActive());
        $this->assertTrue($db->isActiveTransaction());
        $db->close();
        $this->assertFalse($db->isActive());
        $this->assertFalse($db->isActiveTransaction());

        $db = new PdoDatabase('unknown::memory:', '', '');
        $this->expectException(DatabaseException::class);
        $db->open();
    }

    public function testTransactions(): void
    {
        $db = $this->prepareDb(false);

        $this->assertFalse($db->isActiveTransaction());
        $db->beginTransaction();
        $this->assertTrue($db->isActiveTransaction());

        $db->execute('INSERT INTO users (name, email, created) VALUES (:p1, :p2, :p3)', [':p1' => 'transtest', ':p2' => 'email@email.com', ':p3' => '2024-06-20 00:00:00']);

        $db->rollBack();
        $this->assertFalse($db->isActiveTransaction());

        $this->assertEquals(
            0,
            $db->fetchOne('SELECT COUNT(*) AS cnt FROM users WHERE name=:p1', [':p1' => 'transtest'])['cnt']
        );

        $db->beginTransaction();
        $db->execute('INSERT INTO users (name, email, created) VALUES (:p1, :p2, :p3)', [':p1' => 'transtest', ':p2' => 'email@email.com', ':p3' => '2024-06-20 00:00:00']);
        $db->commit();
        $this->assertFalse($db->isActiveTransaction());

        $this->assertEquals(
            1,
            $db->fetchOne('SELECT COUNT(*) AS cnt FROM users WHERE name=:p1', [':p1' => 'transtest'])['cnt']
        );
    }
}
