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

class FunctionAddedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenUsingNotNamespacedFunctionWithNameDifferentThanNewFunctions()
    {
        $this->assertErrors([], 'Checker/FunctionAdded/test1.php');
    }

    public function testItFindsNoErrorWhenUsingNamespacedFunctionWithNameIdenticalToNewFunctions()
    {
        $this->assertErrors([], 'Checker/FunctionAdded/test2.php');
    }

    public function testItFindsErrorWhenUsingNotNamespacedFunctionWithNameIdenticalToNewFunctions()
    {
        $this->assertErrors([
            [3, '"random_int" cannot be used as function name'],
        ], 'Checker/FunctionAdded/test3.php');
    }

    public function testItFindsNoErrorWhenUsingClassMethodWithNameIdenticalToNewFunctions()
    {
        $this->assertErrors([], 'Checker/FunctionAdded/test4.php');
    }
}
