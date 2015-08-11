<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\tests\Functional;

class IntegerHandlingChangedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenNotUsingOctalLiteral()
    {
        $this->assertErrors([], 'Checker/IntegerHandlingChanged/test5.php');
        $this->assertErrors([], 'Checker/IntegerHandlingChanged/test6.php');
    }

    public function testItFindsNoErrorWhenUsingValidOctalLiteral()
    {
        $this->assertErrors([], 'Checker/IntegerHandlingChanged/test1.php');
        $this->assertErrors([], 'Checker/IntegerHandlingChanged/test2.php');
    }

    public function testItFindsErrorWhenUsingInvalidOctalLiteral()
    {
        $this->assertErrors([
            [3, '"0128" is an invalid octal literal and will now triggers a parse error.'],
        ], 'Checker/IntegerHandlingChanged/test3.php');

        $this->assertErrors([
            [3, '"0128" is an invalid octal literal and will now triggers a parse error.'],
        ], 'Checker/IntegerHandlingChanged/test4.php');
    }
}
