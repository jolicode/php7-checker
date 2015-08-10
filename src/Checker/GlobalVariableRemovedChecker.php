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

/**
 * PHP 7 removed the pre-defined variable HTTP_RAW_POST_DATA.
 */
class GlobalVariableRemovedChecker extends AbstractChecker
{
    /** @var string[string] */
    private static $removedGlobals = array(
        'HTTP_RAW_POST_DATA' => 'You can use the php://input stream instead.',
    );

    /** @var bool */
    private $isInGlobalScope = true;

    /** @var string[] */
    private $globalsInCurrentLocalScope = array();

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        // Check if we leave the global scope
        if ($node instanceof Node\FunctionLike) {
            $this->isInGlobalScope = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        // Keep traces of global variable
        if ($node instanceof Node\Stmt\Global_) {
            foreach ($node->vars as $variable) {
                $this->globalsInCurrentLocalScope[] = $variable->name;
            }
        }

        // Check if the variable is marked as global or used in the global scope
        if ($node instanceof Node\Expr\Variable) {
            if ($this->isInGlobalScope || in_array($node->name, $this->globalsInCurrentLocalScope)) {
                $this->checkIfGlobalVariableWasRemoved($node->name, $node->getLine());
            }
        }

        // Check if the variable is used from the $GLOBALS variable
        if ($node instanceof Node\Expr\ArrayDimFetch) {
            if ($node->var instanceof Node\Expr\Variable
                && $node->var->name === 'GLOBALS'
                && $node->dim instanceof Node\Scalar\String_) {
                $this->checkIfGlobalVariableWasRemoved($node->dim->value, $node->dim->getLine());
            }
        }

        // Check if we re-enter in the global scope
        if ($node instanceof Node\FunctionLike) {
            $this->isInGlobalScope = true;
            $this->globalsInCurrentLocalScope = array();
        }
    }

    /**
     * @param string $variableName
     * @param int    $line
     */
    private function checkIfGlobalVariableWasRemoved($variableName, $line)
    {
        if (array_key_exists($variableName, self::$removedGlobals)) {
            $this->errorCollection->add(new Error(
                $this->parserContext->getFilename(),
                $line,
                sprintf('The global variable $%s was removed.', $variableName),
                self::$removedGlobals[$variableName]
            ));
        }
    }
}
