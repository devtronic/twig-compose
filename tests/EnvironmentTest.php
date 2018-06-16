<?php
/**
 * This file is part of the Twig Compose package.
 *
 * Copyright 2018 by Julian Finkler <julian@developer-heaven.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtronic\Tests\TwigCompose;

use Devtronic\TwigCompose\ComposeEnvironment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testCompose()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/res');
        $twig = new ComposeEnvironment($loader);
        $template = $twig->compose('base.html.twig', ['pluginA.html.twig', 'pluginB.html.twig']);
        $this->assertSame(file_get_contents(__DIR__ . '/expected.html'), $template->render(['what' => 'World']));
    }

    public function testFailCompose()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/res');
        $twig = new \Twig_Environment($loader);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('You must add the Devtronic\\TwigCompose\\ComposeExtension to the Twig_Environment');
        ComposeEnvironment::composeStatic($twig, 'base.html.twig', ['pluginA.html.twig', 'pluginB.html.twig']);
    }

    public function testGetSetAutoCompose()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/res');
        $twig = new ComposeEnvironment($loader);
        $this->assertFalse($twig->getAutoCompose());
        $this->assertInstanceOf(ComposeEnvironment::class, $twig->setAutoCompose(true));
        $this->assertTrue($twig->getAutoCompose());
        $this->assertInstanceOf(ComposeEnvironment::class, $twig->setAutoCompose(false));
        $this->assertFalse($twig->getAutoCompose());
    }

    public function testAutoComposeRender()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/res');
        $loader->addPath(__DIR__ . '/res2', 'AutoCompose');
        $twig = new ComposeEnvironment($loader);
        $twig->setAutoCompose(true);
        $this->assertSame(
            file_get_contents(__DIR__ . '/expected.html'),
            $twig->render('base.html.twig', ['what' => 'World'])
        );
    }

    public function testAutoComposeLoad()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/res');
        $loader->addPath(__DIR__ . '/res2', 'AutoCompose');
        $twig = new ComposeEnvironment($loader);
        $twig->setAutoCompose(true);
        $template = $twig->load('base.html.twig');
        $this->assertSame(
            file_get_contents(__DIR__ . '/expected.html'),
            $template->render(['what' => 'World'])
        );
    }
}
