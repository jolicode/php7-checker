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
 * PHP 7 changed a bit list() handling:
 * - Empty list() are no longer supported
 * - list() cannot unpack string anymore (str_split() should be used instead)
 * - list() no longer assigns variables in reverse order.
 *
 * This checker only detects usage of empty list().
 */
class ListHandlingChangedChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Expr\List_) {
            $isEmpty = true;

            foreach ($node->vars as $var) {
                if ($var) {
                    $isEmpty = false;
                }
            }

            if ($isEmpty) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    'Empty list() assignments are no longer supported.',
                    'You should set at least one assignment in the list.'
                ));
            }
        }
    }
}
