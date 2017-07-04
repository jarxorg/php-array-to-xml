# Complete Converter Array to XML

## Install

You can install this package via composer.

```bash
composer require jarxorg/array-to-xml
```

## Examples

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
