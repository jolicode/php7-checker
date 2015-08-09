<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Checker;

use Joli\Php7Checker\Error\Error;
use PhpParser\Node;
use PhpParser\Node\Stmt;

/**
 * PHP 7 added several classes.
 *
 * In consequence, you can no longer use class/interface/trait in the root
 * namespace that has the same name than a class added.
 */
class ClassAddedChecker extends AbstractChecker
{
    private static $addedClasses = array(
        'IntlChar',

        'ReflectionGenerator',
        'ReflectionType',

        'SessionUpdateTimestampHandlerInterface',

        'Throwable',
        'Error',
        'TypeError',
        'ParseError',
        'AssertionError',
        'ArithmeticError',
        'DivisionByZeroError',
    );

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Stmt\Class_
            || $node instanceof Stmt\Interface_
            || $node instanceof Stmt\Trait_) {

            // Check that class is not namespaced and is in the list of added classes
            if (
                isset($node->namespacedName)
                && count($node->namespacedName->parts) === 1
                && in_array($node->name, self::$addedClasses)
            ) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    sprintf('"%s" cannot be used as class/interface/trait name', $node->name),
                    'You should either rename your class/trait/interface or put it in a namespace'
                ));
            }
        }
    }
}
