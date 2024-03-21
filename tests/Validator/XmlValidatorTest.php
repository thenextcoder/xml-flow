<?php

declare(strict_types=1);

namespace TheNextCoder\XmlFlow\Tests\Validator;

use TheNextCoder\XmlFlow\Validator\XmlValidator;
use PHPUnit\Framework\TestCase;

class XmlValidatorTest extends TestCase
{
    public function testWellFormedXmlDoesNotThrowException()
    {
        $xmlString = <<<XML
<?xml version="1.0"?>
<note>
    <to>Tove</to>
    <from>Jani</from>
    <heading>Reminder</heading>
    <body>Don't forget me this weekend!</body>
</note>
XML;

        // Expect not to perform assertions indicates we're testing the absence of exceptions
        $this->expectNotToPerformAssertions();

        XmlValidator::validate($xmlString);
    }

    public function testMalformedXmlThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('XML Parsing Errors:');

        $xmlString = <<<XML
<?xml version="1.0"?>
<note>
    <to>Tove</to>
    <from>Jani</from>
    <!-- Missing closing tag for 'note' element -->
XML;

        XmlValidator::validate($xmlString);
    }
}
