<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Joli\Php7Checker\tests\PHPUnit;

use Joli\Php7Checker\Error\Error;
use Joli\Php7Checker\Error\ErrorCollection;
use Joli\Php7Checker\PHPUnit\SameErrorsConstraint;

class SameErrorsConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testNotAnErrorCollectionAndErrorCollectionDontMatch()
    {
        $constraint = new SameErrorsConstraint(new ErrorCollection());
        $this->assertFalse($constraint->matches(new \stdClass()));
    }

    public function testEmptyErrorCollectionsMatche()
    {
        $constraint = new SameErrorsConstraint(new ErrorCollection());
        $this->assertTrue($constraint->matches(new ErrorCollection()));
    }

    public function testErrorCollectionsWithDifferentSizesDontMatch()
    {
        $error = new Error('toto.php', 1, 'info');

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection2->add($error);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error);
        $errorCollection2->add($error);
        $errorCollection2->add($error);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error);
        $errorCollection1->add($error);
        $errorCollection2->add($error);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));
    }

    public function testErrorCollectionsWithDifferentErrorsDontMatch()
    {
        $error1 = new Error('toto1.php', 1, 'info1');
        $error2 = new Error('toto1.php', 1, 'info2');
        $error3 = new Error('toto1.php', 2, 'info1');
        $error4 = new Error('toto2.php', 1, 'info1');

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection2->add($error2);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection2->add($error3);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection2->add($error4);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection1->add($error2);
        $errorCollection2->add($error1);
        $errorCollection2->add($error3);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));
    }

    public function testErrorCollectionsWithSameErrorsNotInTheSameOrderDontMatch()
    {
        $error1 = new Error('toto1.php', 1, 'info1');
        $error2 = new Error('toto2.php', 2, 'info2');

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection1->add($error2);
        $errorCollection2->add($error2);
        $errorCollection2->add($error1);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertFalse($constraint->matches($errorCollection2));
    }

    public function testErrorCollectionsWithSameErrorsInMatch()
    {
        $error1 = new Error('toto1.php', 1, 'info1');
        $error2 = new Error('toto2.php', 2, 'info2');

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection2->add($error1);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertTrue($constraint->matches($errorCollection2));

        $errorCollection1 = new ErrorCollection();
        $errorCollection2 = new ErrorCollection();

        $errorCollection1->add($error1);
        $errorCollection1->add($error2);
        $errorCollection2->add($error1);
        $errorCollection2->add($error2);

        $constraint = new SameErrorsConstraint($errorCollection1);
        $this->assertTrue($constraint->matches($errorCollection2));
    }
}
