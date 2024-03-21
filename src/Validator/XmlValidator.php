<?php

namespace TheNextCoder\XmlFlow\Validator;

use Exception;
use DOMDocument;

class XmlValidator
{
    /**
     * Validates if an XML string is well-formed.
     *
     * This method checks if the provided XML string is well-formed by attempting to load it into a DOMDocument.
     * It will throw an Exception if the XML is not well-formed, including details of the parsing errors encountered.
     *
     * @param string $xmlContent The XML string to be validated for well-formedness.
     * @throws Exception If the XML is not well-formed or if any other error occurs during parsing.
     */
    public static function validate(string $xmlContent): void
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $isValid = $dom->loadXML($xmlContent, LIBXML_NOBLANKS | LIBXML_NOERROR | LIBXML_NOWARNING);

        if (!$isValid) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            $errorMessages = array_map(function ($error) {
                return sprintf("[%s] %s at line %d, column %d", $error->level, trim($error->message), $error->line, $error->column);
            }, $errors);

            throw new Exception("XML Parsing Errors:\n" . implode("\n", $errorMessages));
        }

        // If no exception was thrown, the XML is well-formed
    }
}
