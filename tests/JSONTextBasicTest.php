<?php

/**
 * @package silverstripe-jsontext
 * @subpackage fields
 * @author Russell Michell <russ@theruss.com>
 * @todo Add 'object' fixture to each
 */

use JSONText\Fields;
use JSONText\Exceptions;

class JSONTextBasicTest extends SapphireTest
{
    /**
     * @var array
     */
    protected $fixtures = [
        'array'     => 'tests/fixtures/json/array.json',
        'object'    => 'tests/fixtures/json/object.json',
        'invalid'   => 'tests/fixtures/json/invalid.json'
    ];

    /**
     * JSONTextTest constructor.
     * 
     * Modify fixtures property to be able to run on PHP <5.6 without use of constant in class property which 5.6+ allows
     */
    public function __construct()
    {
        foreach($this->fixtures as $name => $path) {
            $this->fixtures[$name] = MODULE_DIR . '/' . $path;
        }
    }

    public function testgetValueAsIterable()
    {
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setValue($this->getFixture('invalid'));
        $this->setExpectedException('\JSONText\Exceptions\JSONTextException');
        $this->assertEquals(['chinese' => 'great wall'], $field->getJSONStore());

        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setValue('');
        $this->assertEquals([], $field->getJSONStore());
    }

    public function testFirst()
    {
        // Test: Source data is simple JSON array
        // Return type: Array
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('array', $field->first());
        $this->assertCount(1, $field->first());
        $this->assertEquals([0 => 'great wall'], $field->first());

        // Test: Source data is simple JSON array
        // Return type: JSON
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('json');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('string', $field->first());
        $this->assertEquals('["great wall"]', $field->first());

        // Test: Source data is simple JSON array
        // Return type: SilverStripe
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('silverstripe');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('array', $field->first());
        $this->assertInstanceOf('Varchar', $field->first()[0]);
        $this->assertEquals('great wall', $field->first()[0]->getValue());

        // Test: Empty
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue('');
        $this->assertInternalType('array', $field->first());
        $this->assertCount(0, $field->first());

        // Test: Invalid
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue('{');
        $this->setExpectedException('\JSONText\Exceptions\JSONTextException');
        $field->first();
    }

    public function testLast()
    {
        // Test: Source data is simple JSON array
        // Return type: Array
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('array', $field->last());
        $this->assertCount(1, $field->last());
        $this->assertEquals([6 => 33.3333], $field->last());

        // Test: Source data is simple JSON array
        // Return type: JSON
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('json');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('string', $field->last());
        $this->assertEquals('{"6":33.3333}', $field->last());

        // Test: Source data is simple JSON array
        // Return type: SilverStripe
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('silverstripe');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('array', $field->last());
        $this->assertInstanceOf('Float', $field->last()[6]);
        $this->assertEquals(33.3333, $field->last()[6]->getValue());

        // Test: Empty
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue('');
        $this->assertInternalType('array', $field->last());
        $this->assertCount(0, $field->last());

        // Test: Invalid
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue('{');
        $this->setExpectedException('\JSONText\Exceptions\JSONTextException');
        $field->last();
    }

    public function testNth()
    {
        // Test: Source data is simple JSON array
        // Return type: Array
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('array', $field->nth(1));
        $this->assertCount(1, $field->nth(1));
        $this->assertEquals([1 => true], $field->nth(1));

        // Test: Source data is simple JSON array
        // Return type: JSON
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('json');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('string', $field->nth(1));
        $this->assertEquals('{"1":true}', $field->nth(1));

        // Test: Source data is simple JSON array
        // Return type: SilverStripe
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('silverstripe');
        $field->setValue($this->getFixture('array'));
        $this->assertInternalType('array', $field->nth(1));
        $this->assertInstanceOf('Boolean', $field->nth(1)[1]);
        $this->assertEquals(true, $field->nth(1)[1]->getValue());

        // Test: Empty
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue('');
        $this->assertInternalType('array', $field->nth(1));
        $this->assertCount(0, $field->nth(1));

        // Test: Invalid
        $field = JSONText\Fields\JSONText::create('MyJSON');
        $field->setReturnType('array');
        $field->setValue('{');
        $this->setExpectedException('\JSONText\Exceptions\JSONTextException');
        $field->nth(1);
    }
    
    /**
     * Get the contents of a fixture
     * 
     * @param string $fixture
     * @return string
     */
    private function getFixture($fixture)
    {
        $files = $this->fixtures;
        return file_get_contents($files[$fixture]);
    }

}
