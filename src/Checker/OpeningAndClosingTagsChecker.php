<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Checker;

use Joli\Php7Checker\Error\Error;
use PhpParser\Node;
use PhpParser\Node\Stmt;

/**
 * PHP 7 removes support for alternative PHP tags.
 *
 * In consequence, ASP (<%, <%=, %>) and script (<script language=php>,
 *  </script>) tags can no longer be used to enter/leave PHP mode.
 *
 * The related RFC is https://wiki.php.net/rfc/remove_alternative_php_tags
 */
class OpeningAndClosingTagsChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        /*if ($node instanceof Stmt\
            || $node instanceof Stmt\Interface_
            || $node instanceof Stmt\Trait_) {
            $this->check(
                $node->name,
                $node->getLine(),
                '"%s" is now a reserved type and can no longer be used as the name of a class/interface/trait'
            );
        }

        $this->errorCollection->add(new Error(
            $this->parserContext->getFilename(),
            $line,
            sprintf($message, $type)
        ));*/
    }
}
