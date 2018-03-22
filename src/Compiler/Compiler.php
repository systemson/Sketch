<?php

namespace Amber\Sketch\Compiler;

use Amber\Sketch\Engine\Filesystem;
use Amber\Sketch\Template\Template;

/**
 * The template compiler.
 *
 * This class is in charge of handling the cache file.
 */
class Compiler
{
    /**
     * Make the compiled cache file.
     *
     * @param object $template Amber\Sketch\Template\Template::class
     *
     * @return void
     */
    public function make(Template $template)
    {

        /* Check if the cache file is expired. */
        if ($this->isExpired($template)) {

            /* Write the file in the cache. */
            Filesystem::put(
                $template->cacheName,
                $template->output()
            );
        }
    }

    /**
     * Check if the cache file is expired.
     *
     * @return bool
     */
    public function isExpired(Template $template)
    {

        /* Checks if the cache file don't exists */
        if (!Filesystem::has($template->cacheName)) {
            return true;

        /* Check if the cache file is older than the template */
        } elseif (Filesystem::getTimestamp($template->cacheName) < $template->timestamp()) {
            return true;
        }

        return false;
    }
}
