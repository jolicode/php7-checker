<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Error;

/**
 * An error found by a Checker.
 */
class Error
{
    /** @var string */
    private $filename;

    /** @var int */
    private $line;

    /** @var string */
    private $message;

    /** @var string */
    private $help;

    /**
     * Error constructor.
     *
     * @param string $filename
     * @param int    $line
     * @param string $message
     * @param string $help
     */
    public function __construct($filename, $line, $message, $help = '')
    {
        $this->filename = $filename;
        $this->line = $line;
        $this->message = $message;
        $this->help = $help;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }
}
