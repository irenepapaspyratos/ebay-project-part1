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
     * @param array<string,string|array<string,string>> $nodes An associative array where the keys represent the names of the nodes to be added,
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

            if (!is_string($nodeValue)) {

                $child = $xmlObj->addChild($nodeName);

                foreach ($nodeValue as $firstGenNodeName => $firstGenNodeValue) {

                    if (!is_string($firstGenNodeName) || !is_string($firstGenNodeValue))
                        throw new \Exception('Add child nodes to XML: Invalid type.');

                    $child->addChild($firstGenNodeName, $firstGenNodeValue);
                }
            } else {

                $xmlObj->addChild($nodeName, $nodeValue);
            }
        }

        return is_string($srcXml) ? $xmlObj->asXML() : $xmlObj;
    }
}
