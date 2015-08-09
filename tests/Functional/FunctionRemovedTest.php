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

class FunctionRemovedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenNotUsingRemovedFunction()
    {
        $this->assertErrors([], 'Checker/FunctionRemoved/test1.php');
    }

    public function testItFindsErrorWhenUsingRemovedFunction()
    {
        $this->assertErrors([
            [5, 'Function call_user_method() was removed'],
        ], 'Checker/FunctionRemoved/test3.php');
    }

    // Cannot determine call of classMethod
    /*
    public function testItFindsNoErrorWhenNotUsingRemovedClassMethod()
    {
        $this->assertErrors([], 'Checker/FunctionRemoved/test2.php');
    }

    public function testItFindsErrorWhenUsingRemovedClassMethod()
    {
        $this->assertErrors([
            [5, 'Function IntlDateFormatter::setTimeZoneId() was removed'],
        ], 'Checker/FunctionRemoved/test4.php');
    }
    */
}
