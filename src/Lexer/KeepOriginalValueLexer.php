<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Lexer;

use PhpParser\Lexer\Emulative;
use PhpParser\Parser;

class KeepOriginalValueLexer extends Emulative
{
    public function getNextToken(&$value = null, &$startAttributes = null, &$endAttributes = null)
    {
        $tokenId = parent::getNextToken($value, $startAttributes, $endAttributes);

        if ($tokenId == Parser::T_CONSTANT_ENCAPSED_STRING // non-interpolated string
            || $tokenId == Parser::T_LNUMBER               // integer
            || $tokenId == Parser::T_DNUMBER               // floating point number
        ) {
            // could also use $startAttributes, doesn't really matter here
            $endAttributes['originalValue'] = $value;
        }

        return $tokenId;
    }
}
