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

use Joli\Php7Checker\Error;
use Joli\Php7Checker\ErrorCollection;
use Joli\Php7Checker\Parser;
use Joli\Php7Checker\PHPUnit\SameErrorsConstraint;
use PHPUnit_Framework_TestCase;

class AbstractFunctionalTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @param array  $expectedErrorsAsArray
     * @param string $filename
     * @param string $message
     */
    protected function assertErrors($expectedErrorsAsArray, $filename, $message = '')
    {
        $filename       = dirname(__DIR__).'/fixtures/'.$filename;
        $expectedErrors = new ErrorCollection();

        $parser = new Parser();
        $parser->parseFile($filename);

        foreach ($expectedErrorsAsArray as $errorAsArray) {
            $expectedErrors->add(new Error($filename, $errorAsArray[0], $errorAsArray[1]));
        }

        self::assertThat($parser->getErrorCollection(), new SameErrorsConstraint($expectedErrors), $message);
    }
}
