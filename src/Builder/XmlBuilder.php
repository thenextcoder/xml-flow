<?php

namespace TheNextCoder\XmlFlow\Builder;

use Exception;
use SimpleXMLElement;

class XmlBuilder
{
    private SimpleXMLElement $xml;

    /**
     * XmlBuilder constructor.
     * Allows dynamic root element name and attributes, with optional XML declaration.
     *
     * @param string $rootElementName Name of the root element.
     * @param array $attributes Attributes for the root element.
     * @param bool $includeXmlDeclaration Whether to include the XML declaration.
     * @throws Exception
     */
    public function __construct(string $rootElementName = 'root', array $attributes = [], bool $includeXmlDeclaration = false)
    {
        $rootStr = $includeXmlDeclaration
            ? sprintf('<?xml version="1.0" encoding="UTF-8"?><%s/>', $rootElementName)
            : sprintf('<%s/>', $rootElementName);
        $this->xml = new SimpleXMLElement($rootStr);

        foreach ($attributes as $key => $value) {
            $this->xml->addAttribute($key, $value);
        }
    }

    /**
     * Adds a new element to the XML structure.
     *
     * This method allows for the addition of a new XML element with optional text content and attributes.
     * The new element can be added as a child to either the root element or a specified parent element.
     * The parent can be defined directly as a SimpleXMLElement object or indirectly via an XPath query string that identifies the parent.
     * If the parent is not specified or found, the new element will be added to the root element by default.
     *
     * @param string $elementName The name of the element to add. This parameter is required.
     * @param string|null $value Optional text content to be added within the new element. If not provided, the element will be empty.
     * @param SimpleXMLElement|string|null $parent The parent element to which the new element should be added.
     *        This can be a SimpleXMLElement object representing the parent,
     *        a string containing an XPath query to find the parent,
     *        or null to add the element to the root. If an XPath query is provided but no element is found, the root will be used.
     * @param array $attributes An associative array of attributes to be added to the new element. The array keys are attribute names,
     *        and the corresponding values are the attribute values. This parameter is optional.
     * @return SimpleXMLElement The newly created XML element as a SimpleXMLElement object.
     */
    public function addElement(string $elementName, ?string $value = null, $parent = null, array $attributes = []): SimpleXMLElement
    {
        if (is_string($parent)) {
            $parent = $this->xml->xpath($parent)[0] ?? $this->xml;
        } elseif (!$parent instanceof SimpleXMLElement) {
            $parent = $this->xml;
        }

        $element = $parent->addChild($elementName, $value);
        foreach ($attributes as $key => $val) {
            $element->addAttribute($key, $val);
        }

        return $element;
    }

    /**
     * Retrieve the XML as a string.
     *
     * @return false|string The XML structure as string.
     */
    public function getXml(): false|string
    {
        return $this->xml->asXML();
    }

    /**
     * Retrieve the XML as a formatted string.
     *
     * @return string The formatted XML string.
     */
    public function getFormattedXml(): string
    {
        $dom = dom_import_simplexml($this->xml)->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }
}
