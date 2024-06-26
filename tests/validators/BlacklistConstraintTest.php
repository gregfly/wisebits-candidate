<?php
namespace tests\validators;

use validators\BlacklistConstraint;
use tests\TestCase;

/**
 * BlacklistConstraintTest
 *
 * @author Volkov Grigorii
 */
class BlacklistConstraintTest extends TestCase
{
    public $errorMsg = 'ERROR';

    public function attrsProvider(): array
    {
        return [
            'empty' => ['', true],
            'world' => ['world', true],
            'sentence' => ['just a sentence', true],
            'blackword' => ['blackword', $this->errorMsg],
            'black_word' => ['black_word', $this->errorMsg],
            'blackWord' => ['blackWord', $this->errorMsg],
            'BLACKWORD' => ['BLACKWORD', $this->errorMsg],
        ];
    }

    /**
     * @param string $attrValue
     * @param string|true $expected
     * @return void
     * @dataProvider attrsProvider
     */
    public function testValidateAttrValue(string $attrValue, string|true $expected): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue($attrValue));
        $validator = new BlacklistConstraint(['blackword', 'black_word'], $this->errorMsg);

        $this->assertEquals($expected, $validator->validate($stub, 'attr'));
    }

    public function testEmptyWordInBlacklist(): void
    {
        $stub = $this->createStub('models\IModel');
        $stub->method('getAttribute')->will($this->returnValue('word'));
        $validator = new BlacklistConstraint([''], $this->errorMsg);

        $this->assertEquals($this->errorMsg, $validator->validate($stub, 'attr'));
    }
}
