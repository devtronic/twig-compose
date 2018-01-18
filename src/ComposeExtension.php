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

class ComposeExtension extends \Twig_Extension
{
    public function getNodeVisitors()
    {
        return [
            new ComposeNodeVisitor(),
        ];
    }
}
