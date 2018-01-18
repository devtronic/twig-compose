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

use Twig\NodeVisitor\AbstractNodeVisitor;
use Twig_Environment;
use Twig_Node;

class ComposeNodeVisitor extends AbstractNodeVisitor
{
    /**
     * Called before child nodes are visited.
     *
     * @return Twig_Node The modified node
     */
    protected function doEnterNode(Twig_Node $node, Twig_Environment $env)
    {
        return $node;
    }

    /**
     * Called after child nodes are visited.
     *
     * @return Twig_Node|false The modified node or false if the node must be removed
     */
    protected function doLeaveNode(Twig_Node $node, Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Module) {
            $node->getNode('class_end')->setNode('set_parent', new ComposeNode());
        }
        return $node;
    }

    /**
     * Returns the priority for this visitor.
     *
     * Priority should be between -10 and 10 (0 is the default).
     *
     * @return int The priority level
     */
    public function getPriority()
    {
        return 0;
    }
}
