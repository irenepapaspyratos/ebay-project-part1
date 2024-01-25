<?php

namespace Tests\Unit\Utility;

use App\Utility\XmlUtils;
use Codeception\Test\Unit;

/**
 * The 'XmlUtilsTest' is a unit test class for testing the 'XmlUtils' class.
 * 
 * Tests the functionality of adding nodes to existing XML data given in different types 
 * and the process of assigning declared types to each first level node of an XML object/string.
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
            ],
            'NonSafe' => [
                'amp' => 'blue & green',
                'less' => '1 < 2',
                'more' => '2 > 1',
                'singleQ' => 'say \'Heya!\'',
                'doubleQ' => 'say "Heya!"',
            ],
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
     * It is used for testing the 'getNodesFromXml' method of the 'XmlUtils' class.
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
                'Item',
                [
                    'Title' => 'string',
                    'Price' => 'string'
                ]
            ],
            'Simple XML structure - SimpleXMLElement' => [
                simplexml_load_string($xmlSimple),
                'Item',
                [
                    'Title' => 'string',
                    'Price' => 'string'
                ]
            ],
            'Nested XML structure - string' => [
                $xmlNested,
                'Item',
                [
                    'Description' => 'JSON'
                ]
            ],
            'Nested XML structure - SimpleXMLElement' => [
                simplexml_load_string($xmlNested),
                'Item',
                [
                    'Description' => 'JSON'
                ]
            ],
            'Mixed XML structure - string' => [
                $xmlMixed,
                'Item',
                [
                    'Title' => 'string',
                    'Price' => 'string',
                    'Description' => 'JSON'
                ]
            ],
            'Mixed XML structure - SimpleXMLElement' => [
                simplexml_load_string($xmlMixed),
                'Item',
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
     * the addition of nodes to an XML object works correct regarding
     * the correct output type (depending on the input file) with the expected values (depending on special characters).
     */
    public function testAddNodesToXmlString() {

        // Arrange
        $expect = '<?xml version="1.0"?>' . "\n"
            . '<Item><ItemId>111111111111</ItemId><Color>blue</Color><Description><Size>M</Size></Description><NonSafe><amp><![CDATA[blue & green]]></amp><less><![CDATA[1 < 2]]></less><more><![CDATA[2 > 1]]></more><singleQ><![CDATA[say \'Heya!\']]></singleQ><doubleQ><![CDATA[say "Heya!"]]></doubleQ></NonSafe></Item>' . "\n";

        // Act
        $result = $this->xmlUtils->addNodesToXml($this->validXmlString, $this->validNodes);

        // Assert that the result is a string and equals the expected one
        $this->assertIsString($result);
        $this->assertEquals($expect, $result);
    }

    /**
     * Tests the 'addNodesToXml' method of the 'XmlUtils' class whether 
     * the the addition of nodes to an XML object works correct regarding
     * the correct output type (depending on the input file) with the expected values (depending on special characters).
     */
    public function testAddNodesToXmlObject() {

        // Act
        $result = $this->xmlUtils->addNodesToXml($this->xmlObj, $this->validNodes);

        // Assert that the result is an instance of SimpleXMLElement and equals the expected values
        $this->assertInstanceOf(\SimpleXMLElement::class, $result);
        $this->assertEquals('blue', $result->Color->__toString());
        $this->assertEquals('M', $result->Description->Size->__toString());
        $this->assertEquals('blue & green', $result->NonSafe->amp->__toString());
        $this->assertEquals('1 < 2', $result->NonSafe->less->__toString());
        $this->assertEquals('2 > 1', $result->NonSafe->more->__toString());
        $this->assertEquals('say \'Heya!\'', $result->NonSafe->singleQ->__toString());
        $this->assertEquals('say "Heya!"', $result->NonSafe->doubleQ->__toString());
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
     * Tests the 'extendedSimpleXmlAddChild' method of the 'XmlUtils' class whether 
     * an exception is thrown with the correct error message when an invalid parent node is given.
     */
    public function testExtendedSimpleXmlAddChildFailureByInvalidXmlString() {

        // Assert invalid parent node
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to add nodes to XML');

        //Act
        $this->xmlUtils->extendedSimpleXmlAddChild($this->invalidXmlString, 'Color', 'Blue');
    }

    /**
     * Tests the 'setFirstLevelNodeTypeFromXml' method of the 'XmlUtils' class whether 
     * the corresponding correct array is returned when valid XML is given as input.
     * 
     * An array of test cases will be passed and tested individually by using DataProvider.
     * 
     * @dataProvider validXMLProvider 
     */
    public function testSetFirstLevelNodeTypeFromXml($xmlInput, string $rootNode, array $expectedArray) {

        // Act
        $resultArray = $this->xmlUtils->setFirstLevelNodeTypeFromXml($xmlInput, $rootNode);

        // Assert
        $this->assertEquals($expectedArray, $resultArray);
    }

    /**
     * Tests the 'setFirstLevelNodeTypeFromXml' method of the 'XmlUtils' class whether
     * an exception is thrown with the correct error message when an invalid XML string is given as input.
     */
    public function testSetFirstLevelNodeTypeFromXmlFailureByInvalidXmlInput() {

        // Assert invalid xml string
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Failed to access nodes of XML string: simplexml_load_string\(\):/');

        // Act
        $this->xmlUtils->setFirstLevelNodeTypeFromXml($this->invalidXmlString, 'NotUsedNode');
    }

    /**
     * Tests the 'setFirstLevelNodeTypeFromXml' method of the 'XmlUtils' class whether
     * an exception is thrown with the correct error message when a non-existent root node is given as input.
     */
    public function testSetFirstLevelNodeTypeFromXmlFailureByInvalidRootNode() {

        // Assert invalid root node
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The specified root node \'NotExistingNode\' not existing in the XML.');

        // Act
        $this->xmlUtils->setFirstLevelNodeTypeFromXml($this->validXmlString, 'NotExistingNode');
    }
}
