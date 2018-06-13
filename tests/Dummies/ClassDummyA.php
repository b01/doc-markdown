<?php namespace QL\DocMarkdown\Tests\Dummies;

/**
 * Class ClassDummyA
 *
 * @package \QL\DocMarkdown\Tests\Dummies
 */
class ClassDummyA
{
    /** A string constant. */
    const TEST_1 = '1234';

    /** @var int Field of ClassDummyA */
    private $property1;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->property1 = 1;
    }

    /**
     * Getter for property1.
     *
     * @return int
     */
    public function getProperty1()
    {
        return $this->property1;
    }

    /**
     * Setter for property1.
     *
     * @param $value
     * @return $this
     */
    public function setProperty1($value)
    {
        $this->property1 = $value;

        return $this;
    }
}
?>