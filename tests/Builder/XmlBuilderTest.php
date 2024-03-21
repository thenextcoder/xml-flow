<?php

declare(strict_types=1);

namespace TheNextCoder\XmlFlow\Tests\Builder;

use PHPUnit\Framework\TestCase;
use SimpleXMLElement;
use TheNextCoder\XmlFlow\Builder\XmlBuilder;

class XmlBuilderTest extends TestCase
{
    protected XmlBuilder $xmlBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->xmlBuilder = new XmlBuilder('root', [], true);
    }

    public function testAddElementToRoot()
    {
        $element = $this->xmlBuilder->addElement('item');
        $this->assertInstanceOf(SimpleXMLElement::class, $element);
        $this->assertEquals('item', $element->getName());
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root><item/></root>\n", $this->xmlBuilder->getXml());
    }

    public function testAddElementWithAttributesToRoot()
    {
        $element = $this->xmlBuilder->addElement('item', null, null, ['id' => '1']);
        $this->assertInstanceOf(SimpleXMLElement::class, $element);
        $this->assertEquals('1', $element->attributes()->id);
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root><item id=\"1\"/></root>\n", $this->xmlBuilder->getXml());
    }

    public function testAddElementWithValueToRoot()
    {
        $element = $this->xmlBuilder->addElement('item', 'Value');
        $this->assertInstanceOf(SimpleXMLElement::class, $element);
        $this->assertEquals('Value', (string)$element);
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root><item>Value</item></root>\n", $this->xmlBuilder->getXml());
    }

    public function testAddElementToParent()
    {
        $parent = $this->xmlBuilder->addElement('parent');
        $element = $this->xmlBuilder->addElement('child', 'Value', $parent);
        $this->assertInstanceOf(SimpleXMLElement::class, $element);
        $this->assertEquals('child', $element->getName());
        $this->assertEquals('Value', (string)$element);
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root><parent><child>Value</child></parent></root>\n", $this->xmlBuilder->getXml());
    }

    public function testGetFormattedXml()
    {
        $this->xmlBuilder->addElement('item', 'Value');
        $formattedXml = $this->xmlBuilder->getFormattedXml();
        $expectedXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root>\n  <item>Value</item>\n</root>\n";
        $this->assertEquals($expectedXml, $formattedXml);
    }
}
