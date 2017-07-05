# Complete Converter Array to XML

## Install

You can install this package via composer.

```bash
composer require jarxorg/array-to-xml
```

## Array Specification

```
['<ElementName>', <Attributes|Content|Child>,,,]

Attributes:
   '<AttributeName>' => '<Value>'

Content:
   '<Content>'

Child:
   ['<ElementName>', <Attributes|Content|Child>,,,]
```

## Examples - arrayToXml

```php
use Jarxorg\ArrayToXml;
...
$array = [
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

$result = ArrayToXml::arrayToXml($array);
```

The above example will output something similar to:
```xml
<?xml version="1.0"?>
<html>
  <body>
    <div id="header">Title<span>SubTitle</span>summary</div>
    <div id="main">
      <a href="http://example.com/">Link</a>
    </div>
  </body>
</html>
```

## Examples - xmlToArray

```php
use Jarxorg\ArrayToXml;
...
$xml = '<my-best-fruits>
  <fruit order="1">apple</fruit>
  <fruit order="2">orange</fruit>
  <fruit order="3">kiwi</fruit>
</my-best-fruits>';

$result = ArrayToXml::xmlToArray($xml);
```

The above example will output something similar to:
```xml
[
    'my-best-fruits',
    ['fruit', 'order' => '1', 'apple'],
    ['fruit', 'order' => '2', 'orange'],
    ['fruit', 'order' => '3', 'kiwi']
];
```

## Functions

* `arrayToDom($array, DOMDocument $doc = null)`
* `arrayToXml($array)`
* `domToArray(DOMNode $dom)`
* `xmlToArray($xml)`
