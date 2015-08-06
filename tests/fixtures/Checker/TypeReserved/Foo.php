<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Joli\Php7Checker\tests\fixtures\Checker\TypeReserved;

use DateTime;
use LogicException as ExceptionAliased;
use stdClass as String;

class Foo
{
    // Make usage of the aliased class names to prevent php-cs-fixer to remove them
    public function bar(String $arg)
    {
        $a = new DateTime();
        throw new ExceptionAliased();
    }
}
