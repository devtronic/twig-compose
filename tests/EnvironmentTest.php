<?php

namespace Devtronic\Tests\TwigCompose;

use Devtronic\TwigCompose\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testCompose()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/res');
        $twig = new Environment($loader);
        $template = $twig->compose('base.html.twig', ['pluginA.html.twig', 'pluginB.html.twig']);
        $this->assertSame(file_get_contents(__DIR__ . '/expected.html'), $template->render(['what' => 'World']));
    }
}