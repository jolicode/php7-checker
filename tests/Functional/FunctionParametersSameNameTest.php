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

class FunctionParametersSameNameTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorForFunctionWithDifferentParameters()
    {
        $this->assertErrors([], 'Checker/FunctionParametersSameName/test1.php');
    }

    public function testItFindsErrorForFunctionWithParametersHavingSameName()
    {
        $this->assertErrors([
            [3, 'Functions can\'t have more than one parameter with the same name'],
        ], 'Checker/FunctionParametersSameName/test2.php');

        $this->assertErrors([
            [3, 'Functions can\'t have more than one parameter with the same name'],
        ], 'Checker/FunctionParametersSameName/test3.php');

        $this->assertErrors([
            [3, 'Functions can\'t have more than one parameter with the same name'],
        ], 'Checker/FunctionParametersSameName/test4.php');
    }

    public function testItFindsNoErrorForClassMethodWithDifferentParameters()
    {
        $this->assertErrors([], 'Checker/FunctionParametersSameName/test5.php');
    }

    public function testItFindsErrorForClassMethodWithParametersHavingSameName()
    {
        $this->assertErrors([
            [5, 'Functions can\'t have more than one parameter with the same name'],
        ], 'Checker/FunctionParametersSameName/test6.php');
    }

    public function testItFindsNoErrorForFunctionWithNoParameters()
    {
        $this->assertErrors([], 'Checker/FunctionParametersSameName/test7.php');
    }
}
