<?php namespace QL\DocMarkdown;

/**
 * Class PhpTemplate
 *
 * @package Kshabazz\DocMarkdown
 */
class TwigTemplateEngine implements TemplateEngine
{
    /** @var string */
    private $template;

    /** @var \Twig_Environment */
    private $twig;

    public function loadTemplate()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->template));
    }

    /**
     * @inheritdoc
     */
    public function getFileExtension()
    {
        return 'twig';
    }

    /**
     * @inheritdoc
     */
    public function setTemplate($file)
    {
        $this->template = $file;
    }

    /**
     * @inheritdoc
     */
    public function render($data = null)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->template));

        return $twig->render($data);
    }
}
?>