<?php

namespace Amber\Sketch;

use Amber\Sketch\Compiler\Compiler;
use Amber\Sketch\Config\ConfigAwareInterface;
use Amber\Sketch\Config\ConfigAwareTrait;
use Amber\Sketch\Template\Template;

/**
 * This class handle the template view request.
 */
class Sketch implements ConfigAwareInterface
{
    use Compiler, ConfigAwareTrait;
    /**
     * @var object Amber\Sketch\Template\Template
     */
    public $template;

    /**
     * @var object Amber\Sketch\Compiler\Compiler
     */
    public $compiler;

    /**
     * Instantiates the Sketch class.
     *
     * @param object $template \Amber\Sketch\Template\Template
     * @param object $compiler \Amber\Sketch\Compiler\Compiler
     */
    public function __construct($config)
    {
        $this->setConfig($config);
    }

    public function output()
    {
        ob_start();

        /* Extract the data from the template */
        extract($this->template->getData());

        /* Include the template cache */
        include $this->cache()->getFullPath();

        return ob_get_clean();
    }

    /**
     * Draw the template.
     *
     * @param string $view The relative path to the view.
     * @param array  $data The template data.
     *
     * @return mixed
     */
    public function draw()
    {
        echo $this->output();
    }

    public function setTemplate(Template $template)
    {
        $this->template = $template;
    }

    public function setCompiler(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }
}
