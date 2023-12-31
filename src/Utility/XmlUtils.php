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
     * The `addNodesToXml` method takes an XML string or object and an array of nodes (with or without direct children), 
     * and adds the nodes to the XML.
     * 
     * @param string|\SimpleXMLElement $srcXml XML to receive new nodes.
     * @param array<string,string|int|array<string,string|int>> $nodes An associative array where the keys represent the names of the nodes to be added,
     * and the values represent either the values of the nodes or an array with possible children (also as name => value).
     * 
     * @return string|\SimpleXMLElement Depending on the input parameter type, either an XML string or object will returned.
     * @throws \Exception If an invalid XML string or child nodes are  given as input.
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

        // Add nodes and their possible children to the initial XML
        foreach ($nodes as $nodeName => $nodeValue) {

            if (!is_string($nodeValue) && !is_int($nodeValue)) {

                $child = $xmlObj->addChild($nodeName);

                foreach ($nodeValue as $firstGenNodeName => $firstGenNodeValue) {

                    if (!is_string($firstGenNodeName) || (!is_string($firstGenNodeValue) && !is_int($firstGenNodeValue)))
                        throw new \Exception('Add child nodes to XML: Invalid type.');

                    $child->addChild($firstGenNodeName, $firstGenNodeValue);
                }
            } else {

                $xmlObj->addChild($nodeName, $nodeValue);
            }
        }

        return is_string($srcXml) ? $xmlObj->asXML() : $xmlObj;
    }

    /**
     * The `extractNodesFromXml` method takes an XML string or a SimpleXMLElement object as input and
     * returns an array of the first level child nodes and their assigned types.
     * 
     * A node having children will be assigned to the type 'JSON', otherwise 'string'.
     * 
     * @param string|\SimpleXMLElement $srcXml XML to extract nodes from.
     * 
     * @return array<string,string> Array of nodes extracted from the XML, where each node is represented by name => type.
     * @throws \Exception If an invalid XML string is  given as input.
     */
    public function extractNodesFromXml(string|\SimpleXMLElement $srcXml): array {

        $xmlObj = $srcXml;

        // Convert string to valid object
        if (is_string($srcXml)) {

            try {

                $xmlObj = simplexml_load_string($srcXml);
            } catch (\Exception $e) {

                throw new \Exception('Failed to access nodes of XML string: ' . $e->getMessage());
            }
        }

        // Extract each node of the XML object and assign it to the corresponding type 
        $nodes = [];
        foreach ($xmlObj->Item as $item) {
            foreach ($item->children() as $child) {
                $name = $child->getName();
                $type = (count($child->children()) > 0) ? 'JSON' : 'string';
                $nodes[$name] = $type;
            }
        }

        return $nodes;
    }
}
