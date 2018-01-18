<?php
/**
 * This file is part of the Twig Compose package.
 *
 * Copyright 2018 by Julian Finkler <julian@developer-heaven.de>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtronic\TwigCompose;

use Twig_Environment;
use Twig_LoaderInterface;

class ComposeEnvironment extends Twig_Environment
{
    /**
     * Creates a new Twig_Environment with the compose functionality
     *
     * @param Twig_LoaderInterface $loader
     * @param array $options
     */
    public function __construct(Twig_LoaderInterface $loader, array $options = [])
    {
        parent::__construct($loader, $options);
        $this->addExtension(new ComposeExtension());
    }

    /**
     * Compose a template from different child templates
     *
     * @param string $baseName The name of the base view
     * @param string[] $childNames The names of the child views
     * @return \Twig_Template The composed template
     *
     * @throws \Exception If the ComposeExtension is not loaded
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function compose($baseName, array $childNames)
    {
        return self::composeStatic($this, $baseName, $childNames);
    }

    /**
     * Compose a template from different child templates
     *
     * @param Twig_Environment $twig A Twig_Environment
     * @param string $baseName The name of the base view
     * @param string[] $childNames The names of the child views
     * @return \Twig_Template The composed template
     *
     * @throws \Exception If the ComposeExtension is not loaded
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public static function composeStatic(\Twig_Environment &$twig, $baseName, array $childNames)
    {
        if (!$twig->hasExtension(ComposeExtension::class)) {
            throw new \Exception(sprintf('You must add the %s to the %s', ComposeExtension::class, get_class($twig)));
        }
        $baseTemplate = $twig->resolveTemplate($baseName);

        $prevTemplate = $baseTemplate;
        foreach ($childNames as $childName) {
            $tmpTemplate = $twig->resolveTemplate($childName);
            $tmpTemplate->setParent($prevTemplate);
            $prevTemplate = $tmpTemplate;
        }
        return $prevTemplate;
    }
}
