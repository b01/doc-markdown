<?php namespace QL\DocMarkdown\Tests\Dummies\Traits;
/**
 * This is a Trait file comment.
 */

/**
 * Class TraitA
 *
 * @package QL\DocMarkdown\Tests\Dummies\Traits
 */
trait TraitA
{
    /**
     * This is a public method without public being specified.
     * @param mixed $param1 Any value you want.
     * @param mixed $param2 Any value you want 2.
     *
     * @return mixed A sum of something.
     */
    function sum($param1, $param2)
    {
        return $this->privateMethod1($param1, $param2);
    }

    /**
     * A static method.
     *
     * @param int $param1 An integer.
     * @return int An integer plus whatever static::CONSTANT_1 is set to.
     */
    public static function staticMethod1($param1)
    {
        return 1 + $param1;
    }
}
?>