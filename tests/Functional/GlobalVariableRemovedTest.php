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

class GlobalVariableRemovedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenNotUsingRemovedGlobalVariableInGlobalScope()
    {
        $this->assertErrors([], 'Checker/GlobalVariableRemoved/test1.php');
    }

    public function testItFindsErrorsWhenUsingRemovedGlobalVariableInGlobalScope()
    {
        $this->assertErrors([
            [3, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test2.php');

        $this->assertErrors([
            [3, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test3.php');

        $this->assertErrors([
            [3, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test4.php');

        $this->assertErrors([
            [3, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test5.php');
    }

    public function testItFindsNoErrorWhenNotUsingRemovedGlobalVariableInLocalScope()
    {
        $this->assertErrors([], 'Checker/GlobalVariableRemoved/test6.php');
    }

    public function testItFindsNoErrorWhenUsingVariableInLocalScopeHavingTheSameNameThanRemovedGlobalVariable()
    {
        $this->assertErrors([], 'Checker/GlobalVariableRemoved/test7.php');

        $this->assertErrors([], 'Checker/GlobalVariableRemoved/test8.php');
    }

    public function testItFindsErrorsWhenUsingRemovedGlobalVariableInLocalScope()
    {
        $this->assertErrors([
            [7, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test9.php');

        $this->assertErrors([
            [7, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test10.php');

        $this->assertErrors([
            [5, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test11.php');

        $this->assertErrors([
            [5, 'The global variable "$HTTP_RAW_POST_DATA" was removed'],
        ], 'Checker/GlobalVariableRemoved/test12.php');
    }
}
