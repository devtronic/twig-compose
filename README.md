# ✨ Twig Compose Environment ✨
A twig extension to compose templates from multiple child templates.

## ❓ What does this?
For example you've a base template and N child template which can modify the base Template.
If the templates are fixed, there is no problem you can extend from each other.
Is the number of templates dynamic you can't use extend. In that case you can use this bundle.
Simply call the compose method, pass a base template and a array of sub-templates... and wibbly wobbly you've a template
which contains every content of every child template 🌠

## 📦 Installation
```bash
$ composer require devtronic/twig-compose
```

## 🛠 Usage
For details take a look in the tests directory
### ➡ With a your existing Twig_Environment
```php
<?php

use Devtronic\TwigCompose\ComposeEnvironment;

/** @var $twig Twig_Environment */
$twig->addExtension(new Devtronic\TwigCompose\ComposeExtension());
$template = ComposeEnvironment::composeStatic($twig, 'base.html.twig', ['pluginA.html.twig', 'pluginB.html.twig']);

echo $template->render([]);
```

### ➡ With the ComposeEnvironment
```php
<?php

use Devtronic\TwigCompose\ComposeEnvironment;

$loader = new \Twig_Loader_Filesystem(''); // or whatever
$twig = new ComposeEnvironment($loader); // instead of Twig_Environment

$template = $twig->compose('base.html.twig', ['pluginA.html.twig', 'pluginB.html.twig']);

echo $template->render([]);
```

### ➡ Auto composing templates (Experimental & ComposeEnvironment only)
```php
<?php

use Devtronic\TwigCompose\ComposeEnvironment;

$loader = new \Twig_Loader_Filesystem('/res/main-theme');
$twig = new ComposeEnvironment($loader);
$loader->addPath('/res/theme1', 'Theme1');
$loader->addPath('/res/theme2', 'Theme2');

// If autocompose is enabled, all registered paths will be checked.
// If a file with the the same name (e.g. base.html.twig) exists,
// it will be loaded & composed with the `base.html.twig` inside the main-theme folder
$twig->setAutoCompose(true); 
$template = $twig->load('base.html.twig');

echo $template->render([]);
```