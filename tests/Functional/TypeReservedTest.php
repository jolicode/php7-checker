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

class TypeReservedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenNotUsingReservedTypeAsClassNameOrInNamespace()
    {
        $this->assertErrors([], 'Checker/TypeReserved/Float_.php');
        $this->assertErrors([], 'Checker/TypeReserved/Integer.php');
    }

    public function testItFindsErrorWhenUsingReservedTypeAsClassName()
    {
        $this->assertErrors([
            [5, '"object" is now a reserved type and can no longer be used as the name of a class/interface/trait'],
        ], 'Checker/TypeReserved/Object.php');
    }

    public function testItFindsErrorWhenUsingReservedTypeInNamespace()
    {
        $this->assertErrors([
            [3, '"int" is now a reserved type and can no longer be used in a namespace'],
        ], 'Checker/TypeReserved/Int/Foo.php');
    }

    public function testItFindsNoErrorWhenNotUsingReservedTypeAsAlias()
    {
        $this->assertErrors([], 'Checker/TypeReserved/Bar.php');
    }

    public function testItFindsErrorWhenUsingReservedTypeAsAlias()
    {
        $this->assertErrors([
            [7, '"string" is now a reserved type and can no longer be used as an alias'],
        ], 'Checker/TypeReserved/Foo.php');
    }
}
