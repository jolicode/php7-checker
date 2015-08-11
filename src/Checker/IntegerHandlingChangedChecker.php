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
 * This checker only checks octal literal.
 */
class IntegerHandlingChangedChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Scalar\LNumber) {
            $originalValue = $node->getAttribute('originalValue');

            if ($originalValue && $this->isInvalidOctalLiteral($originalValue)) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    sprintf(
                        '"%s" is an invalid octal literal and will now triggers a parse error.',
                        $originalValue
                    )
                ));
            }
        }
    }

    /**
     * Check if the given string is a valid octal literal.
     *
     * @param string $str
     *
     * @return bool
     */
    private function isInvalidOctalLiteral($str)
    {
        // Handle plain 0 specially
        if ('0' === $str) {
            return false;
        }

        // If first char is 0 (and number isn't 0) it's a special syntax
        if ('0' === $str[0]) {
            // hex
            if ('x' === $str[1] || 'X' === $str[1]) {
                return false;
            }

            // bin
            if ('b' === $str[1] || 'B' === $str[1]) {
                return false;
            }

            for ($i = 1; $i < strlen($str); ++$i) {
                if ($str[$i] > 7) {
                    return true;
                }
            }
        }

        return false;
    }
}
