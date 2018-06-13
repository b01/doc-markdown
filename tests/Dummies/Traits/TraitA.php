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
    /** @var int A private property. */
    private $property1;

    /**
     * Can be anything as there is nothing stopping you from setting them to whatever you want.
     * @var mixed
     */
    public $publicProperty;

    /**
     * It's cool that traits can have private methods, that makes them sharable.
     * However, that kind-of defeats the purpose of private methods does it not?
     * We are going to decorate this with params and return as well.
     *
     * @param mixed $param1 This is the first parameter.
     * @param mixed $param2 This is the second parameter.
     *
     * @return mixed An attempt to produce the summation of $param1 and $param2.
     */
    private function privateMethod1($param1,  $param2)
    {
        return $param1 + $param2;
    }

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
     * This is the summary for $this->sumB method.
     *
     * This is the long description for this method, you can call it if you
     * want, but it really doesn't do anything at all.
     */
    public function sumB()
    {
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