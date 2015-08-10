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

/**
 * New objects cannot be assigned by reference.
 *
 * The result of the new statement can no longer be assigned to a variable by
 * reference. In PHP 5, this behavior triggered an E_DEPRECATED error but in
 * PHP 7, it will trigger a parse error.
 */
class NewAssignmentByReferenceChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\AssignRef) {
            if ($node->expr instanceof Node\Expr\New_) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    'New objects cannot be assigned by reference.'
                ));
            }
        }
    }
}
