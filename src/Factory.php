<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker;

use Joli\Php7Checker\Checker\CheckerInterface;

class Factory
{
    /**
     * @return CheckerInterface[]
     */
    public static function createDefaultCheckers()
    {
        return array(
            new \Joli\Php7Checker\Checker\TypeReservedChecker(),
            new \Joli\Php7Checker\Checker\Php4ConstructorChecker(),
            new \Joli\Php7Checker\Checker\FunctionParametersSameNameChecker(),
            new \Joli\Php7Checker\Checker\GlobalVariableRemovedChecker(),
            new \Joli\Php7Checker\Checker\FunctionRemovedChecker(),
            new \Joli\Php7Checker\Checker\ListHandlingChangedChecker(),
            new \Joli\Php7Checker\Checker\NewAssignmentByReferenceChecker(),
            new \Joli\Php7Checker\Checker\ClassAddedChecker(),
            new \Joli\Php7Checker\Checker\FunctionAddedChecker(),
            new \Joli\Php7Checker\Checker\IntegerHandlingChangedChecker(),
        );
    }

    /**
     * @return Parser
     */
    public static function createParser()
    {
        $parser = new Parser(
            static::createDefaultCheckers()
        );

        return $parser;
    }
}
