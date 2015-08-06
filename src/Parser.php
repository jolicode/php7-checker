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

use Joli\Php7Checker\Error\ErrorCollection;
use PhpParser\Error as PhpParserError;
use PhpParser\Lexer\Emulative;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser as PhpParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Parser
{
    /** @var Factory */
    private $factory;

    /** @var ParserContext */
    private $parserContext;

    /** @var ErrorCollection */
    private $errorCollection;

    /** @var NodeTraverserInterface */
    private $traverser;

    /** @var PhpParser */
    private $parser;

    public function __construct(Factory $factory = null)
    {
        if (!$factory) {
            $factory = new Factory();
        }

        $this->factory = $factory;
        $this->init();
    }

    private function init()
    {
        $this->parser = new PhpParser(new Emulative());
        $this->traverser = new NodeTraverser();

        // Ensure the maximum of name (class, interface, etc) will be resolved to ease process
        $this->traverser->addVisitor(new NameResolver());

        // Register all the checker that should visit the files
        foreach ($this->factory->getCheckers() as $checker) {
            $this->traverser->addVisitor($checker);
        }

        $this->parserContext = $this->factory->getParserContext();
        $this->errorCollection = $this->factory->getErrorCollection();
    }

    public function parseDirectory($directory)
    {
        // Iterate over all .php files in the directory
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $files = new RegexIterator($files, '/\.php$/');

        foreach ($files as $file) {
            $this->parseFile($file);
        }
    }

    /**
     * @param $filename
     */
    public function parseFile($filename)
    {
        try {
            // Keep the context up to date
            $this->parserContext->setFilename($filename);

            // Read the file that should be checked
            $code = file_get_contents($filename);

            // Parse the code
            $stmts = $this->parser->parse($code);

            // Traverse the statements
            $this->traverser->traverse($stmts);
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
