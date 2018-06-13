<?php namespace QL\DocMarkdown;
/**
 * DockMarkdown exception.
 *
 * The exception class deviates from the norm, as the constructor takes an exception code as the first argument, not the
 * last. The idea is to make the code (required and) just as important as the message it represents.
 */

/**
 * Class ExceptionDocMarkdown
 *
 * @package \QL\DocMarkdown
 */
class ExceptionDocMarkdown extends \Exception
{
    /** int Bad directory. */
    const BAD_PATH = 1;

    /** int Default error message. */
    const UNKNOWN = 2;

    /** int Bad glob expression. */
    const BAD_GLOB_EXP = 3;

    /** @var array Error map. */
    /**
     * Map of error messages.
     * This is meant to be shared by all instances of this class, thus static.
     * However, we do not want it to change during run-time, thus private, nor do we want extending classes using the
     * same map.
     * This is also a required property, meaning this class will break when it is not defined. So all extending classes
     * must define it, or they will generate fatal errors.
     *
     * @var array
     */
    static private $errorMap = [
        self::BAD_PATH => 'Invalid file or directory "%s".',
        self::UNKNOWN => 'An unknown error has occurred.',
        self::BAD_GLOB_EXP => 'Bad expression passed to glob "%s".'
    ];

    /**
     * Constructor
     *
     * @param numeric $code Error code.
     * @param array $data data to fill in placeholders for \vsprintf.
     */
    public function __construct($code, array $data = NULL)
    {
        $message = $this->getMessageByCode($code, $data);
        parent::__construct($message, $code);
    }

    /**
     * Convert error code to human readable text.
     *
     * @param numeric & $code
     * @param array $data
     * @return string
     */
    public function getMessageByCode(& $code, array $data = null)
    {
        // If we do not use a reference, then that defeats the purpose of
        // making the $errorMap a static property.
        $map = &static::getErrorMap();

        // When you can't find the code, use a default one.
        if (!\array_key_exists($code, $map)) {
            $code = static::UNKNOWN;
        }

        if (\is_array($data)) {
            return \vsprintf($map[$code], $data);
        }

        return $map[$code];
    }

    /**
     * Since the error map is a static property
     * @return array
     */
    static protected function &getErrorMap()
    {
        return static::$errorMap;
    }
}
?>