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
use PhpParser\NodeVisitor;

/**
 * The interface all checkers should implement.
 */
interface CheckerInterface extends NodeVisitor
{
    /**
     * Set the ErrorCollection that will be used to store errors found by checker.
     *
     * @param ErrorCollection $errors
     */
    public function setErrorCollection(ErrorCollection $errors);

    /**
     * Set the ParserContext to keep some information about the current parsed snippet of code.
     *
     * @param ParserContext $parserContext
     */
    public function setParserContext(ParserContext $parserContext);
}
