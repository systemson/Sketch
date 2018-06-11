<?php

namespace Amber\Sketch;

use Amber\Sketch\Config\Config;
use Amber\Sketch\Compiler\Compiler;
use Amber\Sketch\Template\Template;

/**
 * This class handle the template view request.
 */
class Sketch
{
    use Compiler;
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
        $this->config = $config;
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
        /* Make the cache file */
        //$this->design($this->template);

        /* Extract the data from the response. */
        //extract($this->data);

        /* Include the cache template file. */
        include Config::get('basepath').$this->cache->getPath();
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
