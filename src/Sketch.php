<?php

namespace Amber\Sketch;

use Amber\Sketch\Compiler\Compiler;
use Amber\Sketch\Template\Template;

/**
 * This class handle the template view request.
 */
class Sketch
{
    /**
     * @var object Amber\Sketch\Template\Template::class
     */
    public $template;

    /**
     * @var object Amber\Sketch\Compiler\Compiler::class
     */
    public $compiler;

    /**
     * Instantiate the Sketch class.
     *
     * @param object $template \Amber\Sketch\Template\Template
     * @param object $compiler \Amber\Sketch\Compiler\Compiler
     */
    public function __construct(Template $template, Compiler $compiler)
    {
        $this->template = $template;

        $this->compiler = $compiler;
    }

    /**
     * Draw the template.
     *
     * @param string $view   The relative path to the view.
     * @param array  $data   The template data.
     *
     * @return mixed
     */
    public function draw($view, array $data = [])
    {
        /* Make the cache file */
        $this->compiler->make($this->template);

        /* Extract the data from the response. */
        extract($data);

        /* Include the cache template file */
        include APP_PATH.$this->template->cacheName;
    }
}
