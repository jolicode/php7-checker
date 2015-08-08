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

class Factory
{
    /** @var ParserContext */
    private $parserContext;

    /** @var ErrorCollection */
    private $errorCollection;

    /** @var CheckerInterface[] */
    private $checkers = array();

    /**
     * @return ParserContext
     */
    public function getParserContext()
    {
        if (!$this->parserContext) {
            $this->parserContext = new ParserContext();
        }

        return $this->parserContext;
    }

    /**
     * @return ErrorCollection
     */
    public function getErrorCollection()
    {
        if (!$this->errorCollection) {
            $this->errorCollection = new ErrorCollection();
        }

        return $this->errorCollection;
    }

    /**
     * @return CheckerInterface[]
     */
    public function getCheckers()
    {
        if (empty($this->checkers)) {
            $checkersFQCN = [
                'Joli\Php7Checker\Checker\TypeReservedChecker',
                'Joli\Php7Checker\Checker\Php4ConstructorChecker',
                'Joli\Php7Checker\Checker\OpeningAndClosingTagsChecker',
            ];

            foreach ($checkersFQCN as $fqcn) {
                /** @var CheckerInterface $checker */
                $checker = new $fqcn();
                $checker->setParserContext($this->getParserContext());
                $checker->setErrorCollection($this->getErrorCollection());

                $this->checkers[] = $checker;
            }
        }

        return $this->checkers;
    }
}
