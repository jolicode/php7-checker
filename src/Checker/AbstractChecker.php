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

use Joli\Php7Checker\Error\ErrorCollection;
use Joli\Php7Checker\ParserContext;
use PhpParser\Node;

class AbstractChecker implements CheckerInterface
{
    /** @var ErrorCollection */
    protected $errorCollection;

    /** @var ParserContext */
    protected $parserContext;

    /**
     * {@inheritdoc}
     */
    public function setErrorCollection(ErrorCollection $errorCollection)
    {
        $this->errorCollection = $errorCollection;
    }
    /**
     * {@inheritdoc}
     */
    public function setParserContext(ParserContext $parserContext)
    {
        $this->parserContext = $parserContext;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeTraverse(array $nodes)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(array $nodes)
    {
    }
}
