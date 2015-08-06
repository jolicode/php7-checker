<?php

namespace Joli\Php7Checker\tests\fixtures\Checker\TypeReserved;

use DateTime;
use LogicException as ExceptionAliased;

class Bar
{
    // Make usage of the aliased class names to prevent php-cs-fixer to remove them
    public function foo()
    {
        $a = new DateTime();
        throw new ExceptionAliased();
    }
}
