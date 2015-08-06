<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Joli\Php7Checker\Parser;

use Joli\Php7Checker\Checker\CheckerInterface;
use PhpParser\NodeVisitorAbstract;

class CheckerVisitor extends NodeVisitorAbstract
{
    /** @var CheckerInterface */
    private $checker;

    /**
     * @param CheckerInterface $checker
     */
    public function __construct(CheckerInterface $checker)
    {
        $this->checker = $checker;
    }
}
