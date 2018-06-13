<?php namespace QL\DocMarkdown;

/**
 * Interface TemplateEngine
 *
 * @package Kshabazz\DocMarkdown
 */
interface TemplateEngine
{
    /**
     * Set a template file to be loaded at a later time.
     *
     * @param $file
     * @return $this
     */
    public function setTemplate($file);

    /**
     * Pass data through the template.
     *
     * @param null|array $data Data to pass to the template engine.
     * @return string
     */
    public function render($data = null);
}
?>