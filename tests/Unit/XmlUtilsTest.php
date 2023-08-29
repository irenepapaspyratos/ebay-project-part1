<?php

namespace Tests\Unit\Utility;

use App\Utility\XmlUtils;
use Codeception\Test\Unit;

/**
 * The 'XmlUtilsTest' is a unit test class for testing the 'XmlUtils' class.
 * 
 * Tests the functionality of adding nodes to existing XML data given in different types.
 */
class XmlUtilsTest extends Unit {

    protected $tester;
    private $xmlUtils;
    private $xmlObj;
    private $validXmlString;
    private $validNodes;

    /**
     * Sets up the necessary environment for running tests 
     * by initializing the XmlUtils object and setting valid variables.
     */
    protected function _before() {

        $this->xmlUtils = new XmlUtils();
        $this->xmlObj = simplexml_load_string('<root><note>ABC</note></root>');
        $this->validXmlString = '<root><note>ABC</note></root>';
        $this->validNodes = [
            'item' => 'value',
            'parent' => [
                'child' => 'childValue'
            ]
        ];
    }

    /**
     * The `invalidNodesProvider` function returns an array of test cases 
     * for invalid child nodes in a nested array structure.
     * 
     * @return array An array of test cases will be returned, 
     * where each test case is an array with a single element representing an invalid node structure.
     */
    public function invalidNodesProvider(): array {

        return [

            'invalid child node value - children of second generation' => [[
                'item' => 'value',
                'parent' => [
                    'child' => [
                        'grandchild' => 'grandchildValue'
                    ]
                ]
            ]],
            'invalid child node value - boolean' => [[
                'item' => 'value',
                'parent' => [
                    'child' => true
                ]
            ]],
            'invalid child node - array<boolean>' => [[
                'item' => 'value',
                'parent' => [true]
            ]],
            'invalid child node - array<string>' => [[
                'item' => 'value',
                'parent' => ['abc']
            ]]
        ];
    }

    /**
     * Tests the 'addNodesToXml' method of the 'XmlUtils' class whether 
     * the the addition of nodes to an XML object regarding
     * the correct output type (depending on the input file) with the expected values.
     */
    public function testAddNodesToXmlString() {

        // Arrange
        $expect = '<?xml version="1.0"?>' . "\n"
            . '<root><note>ABC</note><item>value</item><parent><child>childValue</child></parent></root>' . "\n";

        // Act
        $result = $this->xmlUtils->addNodesToXml($this->validXmlString, $this->validNodes);

        // Assert that the result is a string and equals the expected one
        $this->assertIsString($result);
        $this->assertEquals($expect, $result);
    }

    /**
     * Tests the 'addNodesToXml' method of the 'XmlUtils' class whether 
     * the the addition of nodes to an XML object regarding
     * the correct output type (depending on the input file) with the expected values.
     */
    public function testAddNodesToXmlObject() {

        // Arrange
        $expectItenmValue = 'value';
        $expectChildValue = 'childValue';

        // Act
        $result = $this->xmlUtils->addNodesToXml($this->xmlObj, $this->validNodes);

        // Assert that the result is an instance of SimpleXMLElement and equals the expected values
        $this->assertInstanceOf(\SimpleXMLElement::class, $result);
        $this->assertEquals($expectItenmValue, $result->item->__toString());
        $this->assertEquals($expectChildValue, $result->parent->child->__toString());
    }

    /**
     * Tests wether an exception is thrown with the correct error message 
     * when an invalid XML string is given as input.
     */
    public function testAddNodesToXmlFailureByInvalidXmlInput() {

        // Arrange
        $invalidXml = '<root';

        // Assert invalid xml string
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Failed to add nodes to XML: simplexml_load_string\(\):/');

        // Act
        $this->xmlUtils->addNodesToXml($invalidXml, $this->validNodes);
    }

    /**
     * Tests wether an exception is thrown with the correct error message 
     * when an invalid node array is given as input.
     * 
     * An array of test cases will be passed and tested individually by using DataProvider.
     * 
     * @dataProvider invalidNodesProvider 
     */
    public function testAddNodesToXmlFailureByInvalidNodesInput(array $invalidNodes) {

        // Assert invalid type of nodes 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Add child nodes to XML: Invalid type.');

        // Act
        $this->xmlUtils->addNodesToXml($this->validXmlString, $invalidNodes);
    }
}
