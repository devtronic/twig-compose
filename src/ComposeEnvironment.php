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
    private $autoCompose = false;

    private $loadingTemplates = [];

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
        return self::doCompose($twig, $baseTemplate, $childNames);
    }

    private static function doCompose(\Twig_Environment &$twig, &$baseTemplate, array $childNames)
    {
        $prevTemplate = $baseTemplate;
        foreach ($childNames as $childName) {
            $tmpTemplate = $twig->resolveTemplate($childName);
            $tmpTemplate->setParent($prevTemplate);
            $prevTemplate = $tmpTemplate;
        }
        return $prevTemplate;
    }

    private function getTemplatesForAutoCompose($templateName)
    {
        $templates = [];
        foreach ($this->getLoader()->getNamespaces() as $namespace) {
            $tmpNamespace = str_replace(\Twig_Loader_Filesystem::MAIN_NAMESPACE, '', $namespace);
            if (substr($templateName, 0, strlen($tmpNamespace)) != $tmpNamespace) {
                foreach ($this->getLoader()->getPaths($namespace) as $path) {
                    if (is_file($path . '/' . $templateName)) {
                        $templates[] = "@$namespace/$templateName";
                    }
                }
            }
        }

        return $templates;
    }

    public function loadTemplate($name, $index = null)
    {
        if (isset($this->loadingTemplates[$name])) {
            return $this->loadingTemplates[$name];
        }
        $this->preLoadTemplate($name);
        $template = parent::loadTemplate($name, $index);
        $template = $this->postLoadTemplate($name, $template);
        return $template;
    }

    public function preLoadTemplate($name)
    {
        if ($this->autoCompose == false) {
            return;
        }
        $this->loadingTemplates[$name] = null;

    }

    public function postLoadTemplate($name, $template)
    {
        if ($this->autoCompose == false) {
            return $template;
        }

        $this->loadingTemplates[$name] = $template;
        return self::doCompose($this, $template, $this->getTemplatesForAutoCompose($name));
    }

    /**
     * @return bool
     */
    public function getAutoCompose()
    {
        return $this->autoCompose;
    }

    /**
     * @param bool $autoCompose
     * @return ComposeEnvironment
     */
    public function setAutoCompose($autoCompose)
    {
        $this->autoCompose = $autoCompose;
        return $this;
    }
}
