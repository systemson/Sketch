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
    public function design($view, $layout, $data)
    {
        $this->template = new Template($this->viewPath($view), $this->layoutPath($layout), $data, $this->config);
        /* Check if the cache file is expired. */
        $this->setCache($this->template->cache($this->getFolder('cache')));

        if ($this->cacheExpired() || $this->getConfig('enviroment') == 'dev') {
            $this->cache()->setContent($this->template->output());
            $this->cache()->save();
        }
    }

    /**
     * Check if the cache file is expired.
     *
     * @return bool
     */
    public function cacheExpired()
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
