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
 * A collection of Errors.
 */
class ErrorCollection implements \Countable, \IteratorAggregate
{
    /** @var Error[] */
    private $errors = array();

    /**
     * @param Error $e
     */
    public function add(Error $e)
    {
        $this->errors[] = $e;
    }

    /**
     * @return Error[]
     */
    public function all()
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->errors);
    }

    /**
     * Get the error at the given index.
     *
     * @return Error
     */
    public function get($index)
    {
        if (!isset($this->errors[$index])) {
            throw new \OutOfBoundsException(sprintf('No error exists at the index "%s', $index));
        }

        return $this->errors[$index];
    }

    /**
     * Reset the errors list.
     */
    public function reset()
    {
        $this->errors = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->errors);
    }
}
