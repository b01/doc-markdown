<?php namespace Kshabazz\DocMarkdown\Tests\Dummies;
/**
 * File documentation.
 */

/** int Constant documentation. */
const TEST_1 = 1;

/**
 * Return the string "dum fun".
 *
 * @return string
 */
function dumFun()
{
    return 'dum fun';
}

/**
 * Doesn't do anything
 */
function doNothingDummy()
{

}

/**
 * Class AbstractDummyA
 *
 * @package QL\DocMarkdown\Tests\Dummies
 */
abstract class AbstractDummyA
{

    abstract protected function method1();
}
?>