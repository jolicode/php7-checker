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

class NewAssignmentByReferenceTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenUsingNewAssignmentNotByReference()
    {
        $this->assertErrors([], 'Checker/NewAssignmentByReference/test1.php');
    }

    public function testItFindsNoErrorWhenUsingStandardAssignmentByReference()
    {
        $this->assertErrors([], 'Checker/NewAssignmentByReference/test2.php');
    }

    public function testItFindsErrorWhenUsingNewAssignmentByReference()
    {
        $this->assertErrors([
            [3, 'New objects cannot be assigned by reference'],
        ], 'Checker/NewAssignmentByReference/test3.php');

        $this->assertErrors([
            [5, 'New objects cannot be assigned by reference'],
        ], 'Checker/NewAssignmentByReference/test4.php');
    }
}
