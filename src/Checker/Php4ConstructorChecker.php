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
    /** @var string */
    private $currentClass;

    /** @var int */
    private $php4ConstructorLine;

    /** @var bool */
    private $hasNamespace;

    /** @var bool */
    private $hasPhp4Constructor;

    /** @var bool */
    private $hasPhp5Constructor;

    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(array $nodes)
    {
        $this->currentClass = '';
        $this->hasPhp4Constructor = null;
        $this->hasNamespace = false;
        $this->hasPhp4Constructor = false;
        $this->hasPhp5Constructor = false;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        // Check for the name of the class
        if ($node instanceof Stmt\Class_) {
            $this->currentClass = $node->name;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        // Check for namespaced code
        if ($node instanceof Stmt\Namespace_) {
            if ($node->name) {
                $this->hasNamespace = true;
            }
        }

        // Check for class method
        if ($node instanceof Stmt\ClassMethod) {
            if ($node->name === '__construct') {
                $this->hasPhp5Constructor = true;
            }
            if ($this->currentClass && $node->name === $this->currentClass) {
                $this->hasPhp4Constructor = true;
                $this->php4ConstructorLine = $node->getLine();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(array $nodes)
    {
        if (!$this->hasNamespace
            && $this->hasPhp4Constructor
            && !$this->hasPhp5Constructor) {
            $this->errorCollection->add(new Error(
                $this->parserContext->getFilename(),
                $this->php4ConstructorLine,
                'Using a PHP 4 constructor is now deprecated'
            ));
        }
    }
}
