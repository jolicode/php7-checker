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

class ListHandlingChangedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenNotUsingEmptyList()
    {
        $this->assertErrors([], 'Checker/ListHandlingChanged/test1.php');
        $this->assertErrors([], 'Checker/ListHandlingChanged/test5.php');
    }

    public function testItFindsErrorWhenUsingEmptyList()
    {
        $this->assertErrors([
            [3, 'Empty list() assignments are no longer supported'],
        ], 'Checker/ListHandlingChanged/test2.php');

        $this->assertErrors([
            [3, 'Empty list() assignments are no longer supported'],
        ], 'Checker/ListHandlingChanged/test3.php');

        $this->assertErrors([
            [3, 'Empty list() assignments are no longer supported'],
        ], 'Checker/ListHandlingChanged/test4.php');
    }
}
