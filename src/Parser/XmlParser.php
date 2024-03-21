<?php

namespace TheNextCoder\XmlFlow\Parser;

use SimpleXMLElement;
use Exception;

class XmlParser
{
    /**
     * Parses an XML string into a SimpleXMLElement object.
     *
     * This method takes an XML string and converts it into a SimpleXMLElement object for easy manipulation and data extraction.
     * It throws an Exception if the XML string is invalid, empty or cannot be parsed.
     *
     * @param string $xmlString The XML string to be parsed.
     * @return SimpleXMLElement The parsed XML as a SimpleXMLElement object.
     * @throws Exception If the XML string is invalid, empty or cannot be parsed.
     */
    public function parse(string $xmlString): SimpleXMLElement
    {
        if(empty($xmlString)) {
            throw new Exception('Empty XML string provided');
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString);
        $this->handleXmlErrors();

        return $xml;
    }

    /**
     * Handle XML parsing errors.
     *
     * If there are any libxml errors, this function will collect them into a string and throw an exception.
     *
     * @throws Exception If there are libxml errors.
     */
    private function handleXmlErrors(): void
    {
        $errors = libxml_get_errors();
        libxml_clear_errors();
        if ($errors) {
            $errorMessage = "Failed to parse XML: ";
            foreach ($errors as $error) {
                $errorMessage .= $error->message . '; ';
            }
            throw new Exception($errorMessage);
        }
    }

    /**
     * Extracts data from a SimpleXMLElement based on a specified path.
     *
     * This method allows for the extraction of data from a SimpleXMLElement object using an XPath query.
     * The method returns an array of SimpleXMLElement objects that match the specified path.
     * If no matches are found, an empty array is returned.
     *
     * @param SimpleXMLElement $xml The SimpleXMLElement object to query.
     * @param string $path The XPath query string used to find matching elements within the XML.
     * @return array An array of SimpleXMLElement objects that match the specified path.
     */
    public function extractData(SimpleXMLElement $xml, string $path): array
    {
        return $xml->xpath($path) ?: [];
    }

    /**
     * Converts a SimpleXMLElement object to an associative array.
     *
     * This method recursively converts a SimpleXMLElement object, including all of its children, into an associative array.
     * Attributes are also converted and included in the array. This is useful for when an array representation of the XML data is required.
     *
     * @param SimpleXMLElement $xml The SimpleXMLElement object to convert.
     * @return array The converted XML data as an associative array.
     */
    public function toArray(SimpleXMLElement $xml): array
    {
        $array = [];

        // Convert attributes
        foreach ($xml->attributes() as $key => $value) {
            $array[$key] = (string) $value;
        }

        // Convert children
        if ($xml->count() == 0) {
            // If there are no children, just return the value
            $array['_value'] = (string) $xml;
        } else {
            foreach ($xml->children() as $key => $value) {
                // Recurse into child elements
                $childArray = $this->toArray($value);
                // Handle multiple child elements with the same name
                if (isset($array[$key])) {
                    if (!is_array($array[$key]) || !array_key_exists(0, $array[$key])) {
                        $array[$key] = [$array[$key]];
                    }
                    $array[$key][] = $childArray;
                } else {
                    $array[$key] = $childArray;
                }
            }
        }

        return $array;
    }
}
