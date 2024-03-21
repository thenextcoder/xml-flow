<?php

declare(strict_types=1);

namespace TheNextCoder\XmlFlow\Tests\Parser;

use TheNextCoder\XmlFlow\Parser\XmlParser;
use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
{
    protected XmlParser $xmlParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->xmlParser = new XmlParser();
    }

    public function testParseThrowsExceptionForEmptyString()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Empty XML string provided');
        $this->xmlParser->parse('');
    }

    public function testParseThrowsExceptionForInvalidXmlString()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to parse XML');
        $this->xmlParser->parse('<invalid');
    }

    public function testParseWithValidXml()
    {
        $xml = $this->xmlParser->parse('<root><item>Value</item></root>');
        $this->assertEquals('root', $xml->getName());
        $this->assertEquals('Value', (string) $xml->item);
    }

    public function testExtractData()
    {
        $xml = $this->xmlParser->parse('<root><item id="1">Value</item></root>');
        $items = $this->xmlParser->extractData($xml, '/root/item');
        $this->assertCount(1, $items);
        $this->assertEquals('Value', (string) $items[0]);
    }

    public function testToArray()
    {
        $xml = $this->xmlParser->parse('<root><item id="1">Value</item></root>');
        $array = $this->xmlParser->toArray($xml);
        $expectedArray = [
            'item' => [
                'id' => '1',
                '_value' => 'Value',
            ],
        ];
        $this->assertEquals($expectedArray, $array);
    }
}
