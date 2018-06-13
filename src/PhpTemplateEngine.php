<?php namespace QL\DocMarkdown;

/**
 * Class PhpTemplate
 *
 * @package Kshabazz\DocMarkdown
 */
class PhpTemplateEngine implements TemplateEngine
{
    /** @var string */
    private $template;

    /**
     * Load a template file.
     *
     * @param $file
     * @return $this
     */
    public function setTemplate($file, $ext = '.php')
    {
        $this->template = $file . $ext;

        return $this;
    }

    /**
     * @inherit
     */
    public function render($data = null)
    {
        // buffer all output from the include statement.
        \ob_start();

        include $this->template;

        return \ob_get_clean();
    }
}
?>