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

use Twig_Compiler;

class ComposeNode extends \Twig_Node
{
    public function compile(Twig_Compiler $compiler)
    {
        $this->writeSetParentFunction($compiler);
    }

    private function writeSetParentFunction(Twig_Compiler $compiler)
    {
        $compiler
            ->write("\n")
            ->write("public function setParent(\\Twig_Template \$parent)\n")
            ->write("{\n")
            ->indent()
            ->write("\$this->parent = \$parent;\n")
            ->outdent()
            ->write("}\n");
    }
}
