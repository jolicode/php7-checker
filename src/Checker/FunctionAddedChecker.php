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
 * PHP 7 added some new functions.
 *
 * In consequence, you can no longer use function in the root namespace that
 * has the same name than a function added.
 *
 * This checker only detects simple functions, not class methods because it
 * supposes to check if a class extends the given class, then if it defines a
 * method that could overrides a new added one.
 */
class FunctionAddedChecker extends AbstractChecker
{
    private static $addedFunctions = array(
        //'Closure::call',

        'random_bytes',
        'random_int',

        'error_clear_last',

        'gmp_random_seed',

        'intdiv',

        'preg_replace_callback_array',

        'posix_setrlimit',

        //'ReflectionParameter::getType',
        //'ReflectionFunctionAbstract::getReturnType',

        'inflate_add',
        'deflate_add',
        'inflate_init',
        'deflate_init',

    );

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Stmt\Function_) {
            // Check that class is not namespaced and is in the list of added functions
            if (
                isset($node->namespacedName)
                && count($node->namespacedName->parts) === 1
                && in_array($node->name, self::$addedFunctions)
            ) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    sprintf('"%s" cannot be used as function name', $node->name),
                    'You should either rename your function or put it in a namespace'
                ));
            }
        }
    }
}
