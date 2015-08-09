<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Console;

use Joli\Php7Checker\Checker\CheckerInterface;
use Joli\Php7Checker\Console\Command\CheckCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;

class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(-1);

        parent::__construct('PHP 7 Checker', Parser::VERSION);

        $this->add(new CheckCommand());

        $this->setDefaultCommand('check');
    }

    public function getLongVersion()
    {
        $version = parent::getLongVersion().' by <comment>Loïck Piera</comment>';
        $commit = '@git-commit@';

        if ('@'.'git-commit@' !== $commit) {
            $version .= ' ('.substr($commit, 0, 7).')';
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'check';
    }

    /**
     * {@inheritdoc}
     *
     * Overridden so that the application does not expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
