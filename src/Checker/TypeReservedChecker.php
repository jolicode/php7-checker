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
 * PHP 7 prohibits the usage of some types as class, interface and trait names.
 * It also prevents them from being used in class_alias().
 *
 * The related RFC are:
 * - https://wiki.php.net/rfc/reserve_more_types_in_php_7
 * - https://wiki.php.net/rfc/reserve_even_more_types_in_php_7
 */
class TypeReservedChecker extends AbstractChecker
{
    private static $reservedTypes = array(
        'int',
        'float',
        'bool',
        'string',
        'true',
        'false',
        'null',

        // The following types are just reserved but do not yet throw an error.
        // Should we allow to use them? Let's said no for now ;)
        'resource',
        'object',
        'mixed',
        'numeric',
    );

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Stmt\Class_
            || $node instanceof Stmt\Interface_
            || $node instanceof Stmt\Trait_) {
            $this->check(
                $node->name,
                $node->getLine(),
                '"%s" is now a reserved type and can no longer be used as the name of a class/interface/trait'
            );
        } elseif ($node instanceof Stmt\Use_ && $node->type === Stmt\Use_::TYPE_NORMAL) {
            $this->check(
                $node->uses[0]->alias,
                $node->getLine(),
                '"%s" is now a reserved type and can no longer be used as an alias'
            );
        } elseif ($node instanceof Node\Expr\FuncCall
            && $node->name instanceof Node\Name
            && $node->name->parts === array('class_alias')) {
            $args = $node->args;

            if (!empty($args[1]) && $args[1]->value instanceof Node\Scalar\String_) {
                $fqcn = $args[1]->value->value;
                $parts = explode('\\', $fqcn);
                $className = end($parts);

                $this->check(
                    $className,
                    $node->getLine(),
                    '"%s" is now a reserved type and can no longer be used in class_alias()'
                );
            }
        }
    }

    /**
     * @param string $name
     * @param int    $line
     * @param string $message
     */
    private function check($name, $line, $message)
    {
        $name = strtolower($name);

        if (in_array($name, self::$reservedTypes)) {
            $this->errorCollection->add(new Error(
                $this->parserContext->getFilename(),
                $line,
                sprintf($message, $name)
            ));
        }
    }
}
