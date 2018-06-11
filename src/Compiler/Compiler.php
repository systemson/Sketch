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
    public function design(Template $template)
    {
        /* Check if the cache file is expired. */
        //if ($this->isExpired($template) && false) {

        $this->cache = $template->cache();

        $this->cache->setContent($template->output());
        $this->cache->save();
        //}

        $this->template = $template;
    }

    /**
     * Check if the cache file is expired.
     *
     * @return bool
     */
    public function isExpired(Template $template)
    {
        $cacheName = $template->cacheName;

        /* Checks if the cache file don't exists */
        if (!Filesystem::has($cacheName)) {
            return true;
        }

        /* Check if the cache file is older than the template */
        if (Filesystem::getTimestamp($cacheName) < $template->timestamp()) {
            return true;
        }

        return false;
    }
}
