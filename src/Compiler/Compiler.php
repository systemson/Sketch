<?php

namespace Amber\Sketch\Compiler;

use Amber\Filesystem\File;
use Amber\Sketch\Template\Template;

/**
 * The template compiler.
 *
 * This class is in charge of handling the cache file.
 */
trait Compiler
{
    protected $cache;

    public function setCache(File $cache)
    {
        $this->cache = $cache;

        return true;
    }

    public function cache()
    {
        if ($this->cache instanceof File) {
            return $this->cache;
        }

        return $this->cache = $this->template->cache();
    }

    /**
     * Make the compiled cache file.
     *
     * @param object $template Amber\Sketch\Template\Template
     *
     * @return void
     */
    public function design($view, $layout, $data, $override = false)
    {
        $this->template = new Template($view, $layout, $data);

        /* Check if the cache file is expired. */
        $this->setCache($this->template->cache());

        if ($this->isExpired() || $override) {
            $this->cache->setContent($this->template->output());
            $this->cache->save();
        }
    }

    /**
     * Check if the cache file is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->cache()->exists()) {
            return true;
        }

        if ($this->cache()->getTimestamp() < $this->template->getTimestamp()) {
            return true;
        }

        return false;
    }
}
