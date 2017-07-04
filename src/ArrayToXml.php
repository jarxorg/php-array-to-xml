<?php
/**
 * PHP version 5
 *
 * @category  Class
 * @package   Jarxorg
 * @author    uz <uz@jarx.org>
 * @copyright 2017-2018 Copyright
 * @license   Apache License Version 2.0
 * @link      https://github.com/jarxorg/php-array-to-xml
 */
namespace Jarxorg;

use DOMDocument;
use DOMNode;
use DOMException;

/**
 * ArrayToXml is converter for array <-> xml (dom).
 *
 * @category Libraries
 * @package  Jarxorg
 * @author   uz <uz@jarx.org>
 * @license  Apache License Version 2.0
 * @version  Release: 1.0.0
 * @link     https://github.com/jarxorg
 */
class ArrayToXml
{
    /**
     * Converts array to dom node.
     *
     * @param array       $array array
     * @param DOMDocument $doc   dom document
     *
     * @return DOMNode or created DOMDocument
     * @throws DOMException
     */
    public static function arrayToDom($array, DOMDocument $doc = null)
    {
        if (!is_array($array)) {
            throw new DOMException('not array: ' . gettype($array));
        }
        $name = array_shift($array);
        if (!is_string($name)) {
            throw new DOMException('first item must be string: ' . gettype($name));
        }
        $docCreated = false;
        if (!$doc) {
            $doc = new DOMDocument();
            $docCreated = true;
        }
        $elm = $doc->createElement($name);
        foreach ($array as $k => $v) {
            if (is_string($k)) {
                $elm->setAttribute($k, $v);
            } elseif (is_string($v)) {
                $elm->appendChild($doc->createTextNode($v));
            } elseif (is_array($v)) {
                $elm->appendChild(static::arrayToDom($v, $doc));
            }
        }
        if ($docCreated) {
            $doc->appendChild($elm);
            return $doc;
        }
        return $elm;
    }

    /**
     * Converts array to xml string.
     *
     * @param array $array array
     *
     * @return string
     * @throws DOMException
     */
    public static function arrayToXml($array)
    {
        $doc = static::arrayToDom($array);
        $doc->formatOutput = true;
        return $doc->saveXML();
    }

    /**
     * Converts dom to array.
     *
     * @param DOMNode $dom dom node
     *
     * @return array
     */
    public static function domToArray(DOMNode $dom)
    {
        if ($dom->nodeType == XML_DOCUMENT_NODE) {
            $dom = $dom->documentElement;
        }
        $array = [$dom->nodeName];
        if ($dom->hasAttributes()) {
            for ($i = 0; $i < $dom->attributes->length; $i++) {
                $item = $dom->attributes->item($i);
                $array[$item->nodeName] = $item->nodeValue;
            }
        }
        if ($dom->hasChildNodes()) {
            foreach ($dom->childNodes as $child) {
                if ($child->nodeType == XML_TEXT_NODE) {
                    array_push($array, $child->nodeValue);
                } else {
                    array_push($array, static::domToArray($child));
                }
            }
        }
        return $array;
    }

    /**
     * Converts xml to array.
     *
     * @param string $xml xml string
     *
     * @return array
     * @throws DOMException
     */
    public static function xmlToArray($xml)
    {
        $libxmlErrorsOrg = libxml_use_internal_errors();
        if (!$libxmlErrorsOrg) {
            libxml_use_internal_errors(true);
        }
        try {
            $doc = new DOMDocument();
            if (!$doc->loadXML($xml, LIBXML_NOBLANKS)) {
                $msg = 'failed to load xml: ' . json_encode(libxml_get_errors());
                throw new DOMException($msg);
            }
            return static::domToArray($doc);
        } finally {
            libxml_use_internal_errors($libxmlErrorsOrg);
        }
    }
}
