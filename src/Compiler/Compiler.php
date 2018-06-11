<?php

namespace Amber\Sketch\Compiler;

use Amber\Filesystem\Filesystem;
use Amber\Sketch\Template\Template;

/**
 * The template compiler.
 *
 * This class is in charge of handling the cache file.
 */
trait Compiler
{
    public $cache;

    /**
     * Make the compiled cache file.
     *
     * @param object $template Amber\Sketch\Template\Template
     *
     * @return void
     */
    public function design(Template $template, $override = false)
    {
        /* Check if the cache file is expired. */
        $this->cache = $template->cache();

        if ($this->isExpired($template) || $override) {
            $this->cache->setContent($template->output());
            $this->cache->save();
        }

        $this->template = $template;
    }

    /**
     * Check if the cache file is expired.
     *
     * @return bool
     */
    public function isExpired(Template $template)
    {
        $cache = $template->cache();

        if (!Filesystem::has($cache->getPath())) {
            return true;
        }

        if ($cache->getTimestamp() < $template->getTimestamp()) {
            return true;
        }

        return false;
    }
}
