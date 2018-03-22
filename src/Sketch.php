<?php

namespace Amber\Sketch;

use Amber\Sketch\Compiler\Compiler;
use Amber\Sketch\Template\Template;

/**
 * This class handle the template view request.
 */
class Sketch
{
    /** Set private to prevent instance of this class. */
    private function __construct()
    {
    }

    /**
     * Draw the template.
     *
     * @param string $view   The relative path to the view.
     * @param array  $data   The template data.
     * @param array  $config The template config.
     *
     * @return mixed
     */
    public static function draw($view, array $data = [], array $config = [])
    {

        /** @var object Amber\Sketch\Template\Template::class */
        $template = new Template($view, 'app');

        /** @var object Amber\Sketch\Compiler\Compiler::class */
        $compiler = new Compiler();

        /* Make the cache file */
        $compiler->make($template);

        /* Extract the data from the response. */
        extract($data);

        /** Include the cache template file */
        include APP_PATH.$template->cacheName;
    }
}
