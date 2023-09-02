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
    private $invalidXmlString;
    private $validNodes;

    /**
     * Sets up the necessary environment for running tests 
     * by initializing the XmlUtils object and setting valid variables.
     */
    protected function _before() {

        $this->xmlUtils = new XmlUtils();
        $this->xmlObj = simplexml_load_string('<Item><ItemId>111111111111</ItemId></Item>');
        $this->validXmlString = '<Item><ItemId>111111111111</ItemId></Item>';
        $this->invalidXmlString = '<Item';
        $this->validNodes = [
            'Color' => 'blue',
            'Description' => [
                'Size' => 'M'
            ]
        ];
    }

    /**
     * The `invalidNodesProvider` function returns an array of test cases 
     * for invalid child nodes in a nested array structure without giving the expected value to test.
     * 
     * I is used for testing the 'addNodesToXml' method of the 'XmlUtils' class.
     * 
     * @return array An array of test cases will be returned, 
     * where each test case is an array with a single element representing an invalid node structure.
     */
    public function invalidNodesProvider(): array {

        return [

            'invalid child node value - children of second generation' => [[
                'ItemId' => '333333333333',
                'Description' => [
                    'Color' => [
                        'front' => 'grey'
                    ]
                ]
            ]],
            'invalid child node value - boolean' => [[
                'ItemId' => '444444444444',
                'Description' => [
                    'Color' => true
                ]
            ]],
            'invalid child node - array<boolean>' => [[
                'ItemId' => '555555555555',
                'Description' => [true]
            ]],
            'invalid child node - array<string>' => [[
                'ItemId' => '666666666666',
                'Description' => ['fine']
            ]]
        ];
    }

    /**
     * The `validXml` function returns an array of test cases 
     * for various valid XML structures and the expected extracted nodes.
     * 
     * I is used for testing the 'extractNodesFromXml' method of the 'XmlUtils' class.
     * 
     * @return array An array of test cases will be returned, 
     * where each test case is an array with XML input (as string or as SimpleXMLElement) 
     * and the expected nodes output.
     */

    public function validXmlProvider(): array {

        $xmlSimple = '<ItemArray><Item><Title>ABC</Title><Price>123</Price></Item></ItemArray>';
        $xmlNested = '<ItemArray><Item><Description><Color>Red</Color><Size>Large</Size></Description></Item></ItemArray>';
        $xmlMixed = '<ItemArray><Item><Title>ABC</Title><Price>123</Price><Description><Color>Red</Color><Size>Large</Size></Description></Item></ItemArray>';

        return [
            'Simple XML structure - string' => [
                $xmlSimple,
                [
                    'Title' => 'string',
                    'Price' => 'string'
                ]
            ],
            'Simple XML structure - SimpleXMLElement' => [
                simplexml_load_string($xmlSimple),
                [
                    'Title' => 'string',
                    'Price' => 'string'
                ]
            ],
            'Nested XML structure - string' => [
                $xmlNested,
                [
                    'Description' => 'JSON'
                ]
            ],
            'Nested XML structure - SimpleXMLElement' => [
                simplexml_load_string($xmlNested),
                [
                    'Description' => 'JSON'
                ]
            ],
            'Mixed XML structure - string' => [
                $xmlMixed,
                [
                    'Title' => 'string',
                    'Price' => 'string',
                    'Description' => 'JSON'
                ]
            ],
            'Mixed XML structure - SimpleXMLElement' => [
                simplexml_load_string($xmlMixed),
                [
                    'Title' => 'string',
                    'Price' => 'string',
                    'Description' => 'JSON'
                ]
            ],
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
            . '<Item><ItemId>111111111111</ItemId><Color>blue</Color><Description><Size>M</Size></Description></Item>' . "\n";

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
        $expectItemValue = 'blue';
        $expectChildValue = 'M';

        // Act
        $result = $this->xmlUtils->addNodesToXml($this->xmlObj, $this->validNodes);

        // Assert that the result is an instance of SimpleXMLElement and equals the expected values
        $this->assertInstanceOf(\SimpleXMLElement::class, $result);
        $this->assertEquals($expectItemValue, $result->Color->__toString());
        $this->assertEquals($expectChildValue, $result->Description->Size->__toString());
    }

    /**
     * Tests the 'addNodesToXml' method of the 'XmlUtils' class whether
     * an exception is thrown with the correct error message when an invalid XML string is given as input.
     */
    public function testAddNodesToXmlFailureByInvalidXmlInput() {

        // Assert invalid xml string
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Failed to add nodes to XML: simplexml_load_string\(\):/');

        // Act
        $this->xmlUtils->addNodesToXml($this->invalidXmlString, $this->validNodes);
    }

    /**
     * Tests the 'addNodesToXml' method of the 'XmlUtils' class whether 
     * an exception is thrown with the correct error message when an invalid node array is given as input.
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

    /**
     * Tests the 'extractNodesFromXml' method of the 'XmlUtils' class whether 
     * the corresponding correct array is returned when valid XML is given as input.
     * 
     * An array of test cases will be passed and tested individually by using DataProvider.
     * 
     * @dataProvider validXMLProvider 
     */
    public function testExtractNodesFromXml($xmlInput, array $expectedArray) {

        // Act
        $resultArray = $this->xmlUtils->extractNodesFromXml($xmlInput);

        // Assert
        $this->assertEquals($expectedArray, $resultArray);
    }

    /**
     * Tests the 'addNodesToXml' method of the 'XmlUtils' class whether
     * an exception is thrown with the correct error message when an invalid XML string is given as input.
     */
    public function testExtractNodesFromXmlFailureByInvalidXmlInput() {

        // Assert invalid xml string
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Failed to access nodes of XML string: simplexml_load_string\(\):/');

        // Act
        $this->xmlUtils->extractNodesFromXml($this->invalidXmlString, $this->validNodes);
    }
}
