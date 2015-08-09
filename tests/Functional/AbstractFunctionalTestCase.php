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

use Joli\Php7Checker\Factory;
use Joli\Php7Checker\Error\Error;
use Joli\Php7Checker\Error\ErrorCollection;
use Joli\Php7Checker\Parser;
use Joli\Php7Checker\PHPUnit\SameErrorsConstraint;
use PHPUnit_Framework_TestCase;

class AbstractFunctionalTestCase extends PHPUnit_Framework_TestCase
{
    /** @var Parser */
    private static $parser;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        self::$parser = Factory::createParser();
    }

    /**
     * @param array  $expectedErrorsAsArray
     * @param string $filename
     * @param string $message
     */
    protected function assertErrors($expectedErrorsAsArray, $filename, $message = '')
    {
        $errorCollection = self::$parser->getErrorCollection();
        $filename = dirname(__DIR__).'/fixtures/'.$filename;
        $file = new \SplFileInfo($filename);

        $errorCollection->reset();
        self::$parser->parse($file);

        $expectedErrors = new ErrorCollection();
        foreach ($expectedErrorsAsArray as $errorAsArray) {
            $expectedErrors->add(new Error($filename, $errorAsArray[0], $errorAsArray[1]));
        }

        self::assertThat($errorCollection, new SameErrorsConstraint($expectedErrors), $message);
    }
}
