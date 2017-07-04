<?php

namespace Jarxorg;

class ArrayToXmlTest extends \PHPUnit\Framework\TestCase
{
    private static $array1 = [
        'html', [
            'body', [
                'div', 'id' => 'header',
                'Title',
                ['span', 'SubTitle'],
                'summary',
            ], [
                'div', 'id' => 'main',
                ['a', 'href' => 'http://example.com/', 'Link'],
            ],
        ]
    ];

    private static $xml1 = '<?xml version="1.0"?>
<html>
  <body>
    <div id="header">Title<span>SubTitle</span>summary</div>
    <div id="main">
      <a href="http://example.com/">Link</a>
    </div>
  </body>
</html>';

    public function testArrayDom()
    {
        $expected = self::$xml1;
        $doc = ArrayToXml::arrayToDom(self::$array1);
        $doc->formatOutput = true;
        $actual = $doc->saveXML();
        $this->assertEquals(trim($expected), trim($actual));

        $expected = self::$array1;
        $actual = ArrayToXml::domToArray($doc);
        $this->assertEquals($expected, $actual);
    }

    public function testArrayXml()
    {
        $expected = self::$xml1;
        $actual = ArrayToXml::arrayToXml(self::$array1);
        $this->assertEquals(trim($expected), trim($actual));

        $expected = self::$array1;
        $actual = ArrayToXml::xmlToArray($actual);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException DOMException
     * @expectedExceptionMessage not array:
     */
    public function testArrayToDom_NotArrayError()
    {
        ArrayToXml::arrayToDom('');
    }

    /**
     * @expectedException DOMException
     * @expectedExceptionMessage first item must be string:
     */
    public function testArrayToDom_FirstItemError()
    {
        ArrayToXml::arrayToDom([]);
    }

    /**
     * @expectedException DOMException
     * @expectedExceptionMessage failed to load xml:
     */
    public function testXmlToArray_ParseError()
    {
        ArrayToXml::xmlToArray('invalid xml');
    }
}
