<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Joli\Php7Checker\PHPUnit;

use Joli\Php7Checker\Error\Error;
use Joli\Php7Checker\Error\ErrorCollection;

class SameErrorsConstraint extends \PHPUnit_Framework_Constraint
{
    /** @var ErrorCollection */
    private $expectedErrors;

    /**
     * @param ErrorCollection $expectedErrors
     */
    public function __construct(ErrorCollection $expectedErrors)
    {
        $this->expectedErrors = $expectedErrors;

        parent::__construct();
    }

    public function matches($other)
    {
        if (!$other instanceof ErrorCollection) {
            return false;
        }

        if (count($other) !== count($this->expectedErrors)) {
            return false;
        }

        foreach ($this->expectedErrors as $i => $expectedError) {
            if (!$this->matchError($expectedError, $other->get($i))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Error $expectedError
     * @param Error $actualError
     *
     * @return bool
     */
    private function matchError(Error $expectedError, Error $actualError)
    {
        return $expectedError->getFilename() === $actualError->getFilename()
            && $expectedError->getLine() === $actualError->getLine()
            && $expectedError->getInfo() === $actualError->getInfo();
    }

    /**
     * @param ErrorCollection $errors
     *
     * @return string
     */
    private function exportErrors(ErrorCollection $errors)
    {
        $string = '';

        if (count($errors) < 1) {
            $string .= 'an empty ErrorCollection ';
        } else {
            $string .= 'an ErrorCollection with:'.PHP_EOL;
            /** @var Error $error */
            foreach ($errors as $error) {
                $string .= '  > '.$error->getFilename().':'.$error->getLine().PHP_EOL
                          .'        "'.$error->getInfo().'"'.PHP_EOL;
            }
        }

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    protected function failureDescription($other)
    {
        if (!$other instanceof ErrorCollection) {
            return 'errors is an instance of ErrorCollection';
        }

        return $this->exportErrors($other).$this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'is identical to '.trim($this->exportErrors($this->expectedErrors));
    }
}
