<?php

namespace App\Utility;

/**
 * The 'AddElementsToXml' class provides methods to deal with XML objects or strings.
 * 
 * The contained method allows to add nodes with or without children (only first generation) to a given XML object/string 
 * and returns the new XML typed the same as given as parameter.
 */
class XmlUtils {

    /**
     * The 'isCData' method checks via regex match if a given value contains special characters that are 
     * interpreted as markup in XML and will break it, resulting in the need to escape them: <, >, &, ' and ".
     * However, simply escaping would alter the original string, which has to be avoided sometimes. 
     * In such cases a CData section can be used for a node value with such characters.
     * 
     * @param string $value Node value as string to be checked.
     * 
     * @return boolean True if the given value contains any non-safe characters, false otherwise.
     */
    private function needsCData(string $value): bool {

        return preg_match('/[<>&\'"]/', $value) > 0;
    }

    /**
     * The 'addChildWithCData' method adds a child node with a CData section as node value 
     * to a given parent node in a SimpleXMLElement object using the 'DOMDocument' class.
     * 
     * CData sections are used to escape special characters in XML content ( <, >, &, ' and "), so that they are 
     * not interpreted as markup and the underlying string stays as it is instead of with the use of e.g. &amp; for & etc..
     * 
     * @param \SimpleXMLElement $parentNode Element to add the new child to.
     * @param string $childName Name of the child element to be added.
     * @param string $childValue Content to be added as CDATA section to the child element. 
     * 
     * @return \SimpleXMLElement New child node as \SimpleXMLElement object.
     */
    private function addChildWithCData(\SimpleXMLElement $parentNode, string $childName, string $childValue): \SimpleXMLElement {
        $child = $parentNode->addChild($childName);

        $node = dom_import_simplexml($child);
        $doc = $node->ownerDocument;
        $node->appendChild($doc->createCDATASection($childValue));

        return $child;
    }

    /**
     * The 'extendedSimpleXmlAddChild' method adds a child element 
     * with a given name and value to a parent SimpleXMLElement object. 
     * If the child value needs to be wrapped in a CDATA section is being checked with the 'needsCData' method.
     * If positive, it uses the help of the 'addChildWithCData' method instead of the 'addChild' method of the SimpleXml Class. 
     * 
     * @param \SimpleXMLElement $parentNode Node to which the child node will be added. 
     * @param string $childName Name of the child node to be added.
     * @param string $childValue Content to be assigned to the child element.
     * 
     * @return \SimpleXMLElement a \SimpleXMLElement object of the added child node.
     * @throws \Exception If an invalid XML string is given as input.
     */
    public function extendedSimpleXmlAddChild(string|\SimpleXMLElement $parentNodeSrc, string $childName, string $childValue): \SimpleXMLElement {

        $parentNode = $parentNodeSrc;

        // Convert string to valid object
        if (is_string($parentNodeSrc)) {

            try {

                $parentNode = simplexml_load_string($parentNodeSrc);
            } catch (\Exception $e) {

                throw new \Exception('Failed to add nodes to XML: ' . $e->getMessage());
            }
        }

        return $this->needsCData($childValue) ? $this->addChildWithCData($parentNode, $childName, $childValue) : $parentNode->addChild($childName, $childValue);
    }

    /**
     * The `addNodesToXml` method takes an XML string or object and adds an array of nodes (with or without direct children) to it.
     * If a node value contains non-safe special characters, a CData section will be added instead of the bare value 
     * using the customized 'extendedSimpleXmlAddChild' method.
     * 
     * @param string|\SimpleXMLElement $srcXml XML to receive new nodes.
     * @param array<string,string|int|array<string,string|int>> $nodes An associative array where the keys represent the names of the nodes to be added,
     * and the values represent either the values of the nodes or an array with possible children (also as name => value).
     * 
     * @return string|\SimpleXMLElement Depending on the input parameter type, either an XML string or object will returned.
     * @throws \Exception If an invalid XML string or child nodes are given as input.
     */
    public function addNodesToXml(string|\SimpleXMLElement $srcXml, array $nodes): string|\SimpleXMLElement {

        $xmlObj = $srcXml;

        // Convert string to valid object
        if (is_string($srcXml)) {

            try {

                $xmlObj = simplexml_load_string($srcXml);
            } catch (\Exception $e) {

                throw new \Exception('Failed to add nodes to XML: ' . $e->getMessage());
            }
        }

        // Add nodes and their possible children (first level only) to the initial XML
        foreach ($nodes as $nodeName => $nodeValue) {

            if (!is_string($nodeValue) && !is_int($nodeValue)) {

                $child = $xmlObj->addChild($nodeName);

                foreach ($nodeValue as $firstGenNodeName => $firstGenNodeValue) {

                    if (!is_string($firstGenNodeName) || (!is_string($firstGenNodeValue) && !is_int($firstGenNodeValue)))
                        throw new \Exception('Add child nodes to XML: Invalid type.');

                    // Add node with plain value or CData section to child
                    $this->extendedSimpleXmlAddChild($child, $firstGenNodeName, $firstGenNodeValue);
                }
            } else {

                // Add node with plain value or CData section to XML object
                $this->extendedSimpleXmlAddChild($xmlObj, $nodeName, $nodeValue);
            }
        }

        return is_string($srcXml) ? $xmlObj->asXML() : $xmlObj;
    }

    /**
     * The `setFirstLevelNodeTypeFromXml` method takes an XML string or a SimpleXMLElement object as input and 
     * returns an array of the first level child nodes and their assigned types starting from the specified root node.
     * A node having children will be assigned to the type 'JSON', otherwise 'string'.
     * 
     * @param string|\SimpleXMLElement $srcXml XML to extract nodes from.
     * @param string $rootNode The root node from which to start extracting child nodes.
     * 
     * @return array<string,string> Array of nodes extracted from the XML, where each node is represented by name => type.
     * @throws \Exception If an invalid XML string or root node is  given as input.
     */
    public function setFirstLevelNodeTypeFromXml(string|\SimpleXMLElement $srcXml, string $rootNode): array {

        $xmlObj = $srcXml;

        // Convert string to valid object
        if (is_string($srcXml)) {

            try {

                $xmlObj = simplexml_load_string($srcXml);
            } catch (\Exception $e) {

                throw new \Exception('Failed to access nodes of XML string: ' . $e->getMessage());
            }
        }

        // Check if the specified root node exists
        if (!isset($xmlObj->$rootNode))
            throw new \Exception("The specified root node '{$rootNode}' not existing in the XML.");

        // Extract each node of the XML object starting from the specified root node and assign it to the corresponding type 
        $nodes = [];
        foreach ($xmlObj->$rootNode as $item) {

            foreach ($item->children() as $child) {

                $name = $child->getName();
                $type = (count($child->children()) > 0) ? 'JSON' : 'string';
                $nodes[$name] = $type;
            }
        }

        return $nodes;
    }
}
