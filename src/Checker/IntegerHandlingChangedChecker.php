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
 * PHP 7 changed a bit integer handling:
 * - Invalid octal literal now trigger a parse error instead of silently
 * truncated (0128 was taken as 012)
 * - Bitwise shifts by negative numbers will now throw an ArithmeticError
 * - Bitwise shifts (in either direction) beyond the bit width of an integer
 * will always result in 0 (no longer architecture dependent).
 *
 * This checker only checks octal literal and bitwise shifts by negative
 * numbers.
 */
class IntegerHandlingChangedChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Scalar\LNumber) {
            $isEmpty = false;

            dump($node);

            if ($isEmpty) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    'Invalid octal literal now trigger parse error'
                ));
            }
        }
    }
}
