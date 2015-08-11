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
use Joli\Php7Checker\Error\ErrorCollection;
use Joli\Php7Checker\Lexer\KeepOriginalValueLexer;
use PhpParser\Error as PhpParserError;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser as PhpParser;

class Parser
{
    const VERSION = '0.1.0';

    /** @var PhpParser */
    private $parser;

    /** @var NodeTraverserInterface */
    private $traverser;

    /** @var ParserContext */
    private $parserContext;

    /** @var ErrorCollection */
    private $errorCollection;

    /**
     * @param CheckerInterface[] $checkers
     */
    public function __construct(array $checkers)
    {
        $this->parser = new PhpParser(new KeepOriginalValueLexer());
        $this->traverser = new NodeTraverser();
        $this->parserContext = new ParserContext();
        $this->errorCollection = new ErrorCollection();

        // Resolve fully qualified name (class, interface, function, etc) to ease some process
        $this->traverser->addVisitor(new NameResolver());

        // Register all the checker that should visit the parsed files
        foreach ($checkers as $checker) {
            $checker->setParserContext($this->parserContext);
            $checker->setErrorCollection($this->errorCollection);
            $this->traverser->addVisitor($checker);
        }
    }

    /**
     * Parse the given file to check potential errors.
     *
     * @param \SplFileInfo $file
     */
    public function parse(\SplFileInfo $file)
    {
        try {
            // Open the file
            $file = $file->openFile();

            // Keep the context up to date
            $this->parserContext->setFilename($file->getRealPath());

            if ($file->getSize() > 0) {
                // Parse the file content
                $stmts = $this->parser->parse($file->fread($file->getSize()));

                // Traverse the statements
                $this->traverser->traverse($stmts);
            }
        } catch (PhpParserError $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }

    /**
     * @return ErrorCollection
     */
    public function getErrorCollection()
    {
        return $this->errorCollection;
    }
}
