# Twig Compose Environment
A **quick and dirty** solution for multiple sub templates in twig

## Installation
```bash
$ composer require devtronic/twig-compose
```

## Usage

Every sub template **must** extend from the base template 
```php
<?php
$loader = new \Twig_Loader_Filesystem(''); // or whatever
$twig = new Devtronic\TwigCompose\Environment($loader); // instead of Twig_Environment

$template = $twig->compose('base.html.twig', ['pluginA.html.twig', 'pluginB.html.twig']); // Take a look in tests/res

echo $template->render([
    // Your template data
]);

```