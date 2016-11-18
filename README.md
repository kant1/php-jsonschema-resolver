# PHP-JSONSCHEMA-RESOLVER

This package allow you to parse a JSONSchema file and it can resolve the "ref" operator.

```
<?php
require_once __DIR__.'/vendor/autoload.php';

$json = Kant1\JSONElement::fromFile(__DIR__.'/json/simple.json');

foreach($json['paths'] as $url=>$d){
    print $url." : ";
    foreach($d as $method=>$d){
        print $method."\n";
    }
}
```
