# XMLFlow

[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

XMLFlow is a simple, fast, lightweight, and easy-to-use PHP library for building, parsing, and validating XML documents. It can also be used to build highly structured prompts for LLM.

## Features

1. Build XML documents programmatically with `XmlBuilder`
2. Parse any XML data into PHP arrays or objects
3. Validate the syntax and structure of your XML data

## Installation

You can install XMLFlow via [Composer](https://getcomposer.org/):

```bash
composer require thenextcoder/xmlflow
```

You will then be able to import XMLFlow in your PHP scripts like this:

```php
use TheNextCoder\XmlFlow\Builder\XmlBuilder;
```

## Usage XmlBuilder

### Example 1: Creating a Simple Document

This example demonstrates how to create a simple XML document with a custom root element and a few child elements.

```php
use TheNextCoder\XmlFlow\Builder\XmlBuilder;

$xmlBuilder = new XmlBuilder('greeting');
$xmlBuilder->addElement('hello', 'World');
$xmlBuilder->addElement('goodbye', 'See you later');

echo $xmlBuilder->getFormattedXml();
```

**Output:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<greeting>
  <hello>World</hello>
  <goodbye>See you later</goodbye>
</greeting>
```

### Example 2: Nested Elements with Attributes

This example shows how to create an XML document with nested elements and attributes, illustrating the use of XPath to specify the parent element.

```php
$xmlBuilder = new XmlBuilder('book', ['isbn' => '000-0-00-000000-0']);
$chapter = $xmlBuilder->addElement('chapter', 'Introduction to XML', null, ['number' => '1']);
$xmlBuilder->addElement('section', 'Basics of XML', $chapter, ['id' => 'section-1']);

echo $xmlBuilder->getFormattedXml();
```

**Output:**

```xml
<book isbn="000-0-00-000000-0">
  <chapter number="1">Introduction to XML
    <section id="section-1">Basics of XML</section>
  </chapter>
</book>
```

### Example 3: Using XPath to Add Elements

Illustrates adding elements to a specified parent using XPath, useful for more complex document structures.

```php
$xmlBuilder = new XmlBuilder('library');
$xmlBuilder->addElement('shelf', null, null, ['id' => 'shelf-1']);
$xmlBuilder->addElement('book', 'XML for Dummies', '//shelf[@id="shelf-1"]', ['author' => 'John Doe']);

echo $xmlBuilder->getFormattedXml();
```

**Output:**

```xml
<library>
  <shelf id="shelf-1">
    <book author="John Doe">XML for Dummies</book>
  </shelf>
</library>
```

### Example 4: Complex Document Creation

This example creates a more complex XML document, demonstrating the class's flexibility.

```php
$xmlBuilder = new XmlBuilder('catalog');
$products = $xmlBuilder->addElement('products');
for ($i = 1; $i <= 3; $i++) {
    $product = $xmlBuilder->addElement('product', "Product $i", $products);
    $xmlBuilder->addElement('price', '$' . (10 * $i), $product, ['currency' => 'USD']);
}

echo $xmlBuilder->getFormattedXml();
```

**Output:**

```xml
<catalog>
  <products>
    <product>
      <price currency="USD">$10</price>Product 1
    </product>
    <product>
      <price currency="USD">$20</price>Product 2
    </product>
    <product>
      <price currency="USD">$30</price>Product 3
    </product>
  </products>
</catalog>
```

## Usage XmlParser

### Example 1: Parsing XML Data

This example demonstrates how to parse an XML string into a PHP array.

```php

use TheNextCoder\XmlFlow\Parser\XmlParser;

$xmlString = <<<XML
<task>
    <title>Write a documentation</title>
    <priority>High</priority>
    <subtasks>
        <subtask>Outline the main sections and subtopics</subtask>
        <subtask>Write the introductory overview</subtask>
        <subtask>Draft the "getting started" or installation guide</subtask>
        <subtask>Detail the main functionalities and their uses</subtask>
        <subtask>Explain any advanced features or options</subtask>
        <subtask>Write troubleshooting tips or FAQs section</subtask>
        <subtask>Include screenshots, diagrams, or other visual aids</subtask>
        <subtask>Address any known issues or limitations</subtask>
        <subtask>Indicate on how users can submit comments or questions</subtask>
        <subtask>Proofread for clarity, accuracy, and grammar</subtask>
        <subtask>Solicit feedback from colleagues or beta testers</subtask>
        <subtask>Make necessary revisions based on feedback</subtask>
        <subtask>Finalize and publish the documentation</subtask>
    </subtasks>
</task>
XML;

$parser = new XmlParser();

try {
    $xml = $parser->parse($xmlString);
    
    // Extracting title and priority
    echo "Task: " . $xml->title . "\n";
    echo "Priority: " . $xml->priority . "\n\n";
    
    // Extracting and listing subtasks
    echo "Subtasks:\n";
    foreach ($xml->subtasks->subtask as $subtask) {
        echo "- " . $subtask . "\n";
    }

    // Optional: Converting to an associative array
    $arrayRepresentation = $parser->toArray($xml);
    echo "\nArray Representation:\n";
    print_r($arrayRepresentation);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
## Usage XmlValidator

### Example 1: Validating XML Data

This example demonstrates how to validate the syntax and structure of an XML string.

```php
use TheNextCoder\XmlFlow\Validator\XmlValidator;

$xmlContent = '<root><child>Example</child></root>'; // Your XML content here

try {
    XmlValidator::validate($xmlContent);
    echo "The XML is well-formed.";
} catch (Exception $e) {
    echo "The XML is not well-formed. Errors: " . $e->getMessage();
}
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for more details.

## License

XMLFlow is open-source software licensed under the [MIT license](LICENSE).
