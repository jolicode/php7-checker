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

class FunctionParametersSameNameChecker extends AbstractChecker
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\FunctionLike) {
            $params = $node->getParams();
            $paramNames = array();
            $hasParameterWithSameName = false;

            array_walk(
                $params,
                function (Node\Param $param) use (&$paramNames, &$hasParameterWithSameName) {
                    if (array_key_exists($param->name, $paramNames)) {
                        $hasParameterWithSameName = true;
                    }
                    $paramNames[$param->name] = true;
                }
            );

            if ($hasParameterWithSameName) {
                $this->errorCollection->add(new Error(
                    $this->parserContext->getFilename(),
                    $node->getLine(),
                    'Functions can\'t have more than one parameter with the same name'
                ));
            }
        }
    }
}
