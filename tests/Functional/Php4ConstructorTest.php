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

class Php4ConstructorTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWithPhp5Constructor()
    {
        $this->assertErrors([], 'Checker/Php4Constructor/Squirtle.php');
    }

    public function testItFindsNoErrorWithPhp4ConstructorAndNamespace()
    {
        $this->assertErrors([], 'Checker/Php4Constructor/Bulbasaur.php');
    }

    public function testItFindsNoErrorWithPhp4AndPhp5Constructors()
    {
        $this->assertErrors([], 'Checker/Php4Constructor/Charmander.php');
    }

    public function testItFindsNoErrorWithPhp4AndPhp5ConstructorsAndNamespace()
    {
        $this->assertErrors([], 'Checker/Php4Constructor/Mew.php');
    }

    public function testItFindsErrorWithPhp4ConstructorOnly()
    {
        $this->assertErrors([
            [5, 'Using a PHP 4 constructor is now deprecated.'],
        ], 'Checker/Php4Constructor/Pikachu.php');
    }

    public function testItFindsErrorWithPhp4ConstructorAndRootNamespace()
    {
        $this->assertErrors([
            [6, 'Using a PHP 4 constructor is now deprecated.'],
        ], 'Checker/Php4Constructor/Jigglypuff.php');
    }
}
