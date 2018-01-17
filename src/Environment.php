<?php

namespace Devtronic\TwigCompose;

use Twig_Environment;
use Twig_Loader_Array;

class Environment extends Twig_Environment
{
    /**
     * Compose a template with N sub templates
     *
     * @param string $baseTemplate The base template
     * @param string[] $subTemplates The sub templates
     *
     * @return \Twig_TemplateWrapper The Template
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function compose($baseTemplate, $subTemplates)
    {
        $baseTemplatePath = $this->getExactPath($baseTemplate);
        $templates = [$baseTemplate => file_get_contents($baseTemplatePath)];
        foreach ($subTemplates as $template) {
            $templates[md5($template . uniqid()) . '.html.twig'] = file_get_contents($this->getExactPath($template));
        }

        $i = 0;
        $prevKey = null;
        foreach ($templates as $hash => $template) {
            if ($i < 2) {
                $i++;
                $prevKey = $hash;
                continue;
            }

            if (strstr($template, $baseTemplatePath)) {
                $template = str_replace($baseTemplatePath, $prevKey, $template);
            }

            if (strstr($template, $baseTemplate)) {
                $template = str_replace($baseTemplate, $prevKey, $template);
            }
            $templates[$hash] = $template;
            $prevKey = $hash;
        }
        end($templates);
        $newTemplate = key($templates);

        $loader = new Twig_Loader_Array($templates);
        $originalLoader = $this->getLoader();
        $this->setLoader($loader);
        $templateWrapper = $this->load($newTemplate);
        $this->setLoader($originalLoader);

        return $templateWrapper;
    }

    /**
     * Gets the exact template path
     *
     * @param string $name The template name
     * @return string The template path
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    private function getExactPath($name)
    {
        return $this->resolveTemplate($name)->getSourceContext()->getPath();
    }
}
