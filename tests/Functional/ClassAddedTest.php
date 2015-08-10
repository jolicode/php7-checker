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

class ClassAddedTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWhenUsingNotNamespacedClassWithNameDifferentThanNewClasses()
    {
        $this->assertErrors([], 'Checker/ClassAdded/Foo.php');
    }

    public function testItFindsNoErrorWhenUsingNamespacedClassWithNameIdenticalToNewClasses()
    {
        $this->assertErrors([], 'Checker/ClassAdded/Error.php');
    }

    public function testItFindsErrorWhenUsingNotNamespacedClassWithNameIdenticalToNewClasses()
    {
        $this->assertErrors([
            [3, '"Throwable" cannot be used as class/interface/trait name.'],
        ], 'Checker/ClassAdded/Throwable.php');

        $this->assertErrors([
            [4, '"IntlChar" cannot be used as class/interface/trait name.'],
        ], 'Checker/ClassAdded/IntlChar.php');
    }
}
