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
 * PHP 7 emits E_DEPRECATED whenever a PHP 4 constructor is defined.
 *
 * A PHP 4 constructor is a method that have the same name than its class. The
 * only few cases were this method would be considered as a constructor is when
 * no PHP 5 constructor (__construct) is defined and when the class is in the
 * global namespace.
 *
 * The related RFC is: https://wiki.php.net/rfc/remove_php4_constructors
 */
class Php4ConstructorChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Stmt\Class_) {
            $currentClassName = $node->name;
            $hasNamespace = false;
            $hasPhp4Constructor = false;
            $hasPhp5Constructor = false;
            $php4ConstructorLine = null;

            if (count($node->namespacedName->parts) > 1) {
                $hasNamespace = true;
            }

            foreach ($node->stmts as $stmt) {
                // Check for constructors
                if ($stmt instanceof Stmt\ClassMethod) {
                    if ($stmt->name === '__construct') {
                        $hasPhp5Constructor = true;
                    }

                    if ($stmt->name === $currentClassName) {
                        $hasPhp4Constructor = true;
                        $php4ConstructorLine = $stmt->getLine();
                    }
                }
            }

            if (!$hasNamespace && $hasPhp4Constructor && !$hasPhp5Constructor) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $php4ConstructorLine,
                    'Using a PHP 4 constructor is now deprecated.',
                    'You should use the __construct() method instead.'
                ));
            }
        }
    }
}
