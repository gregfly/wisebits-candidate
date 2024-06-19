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

        $db = new PdoDatabase('unknown::memory:', '', '');
        $this->expectException(DatabaseException::class);
        $db->open();
    }
}
