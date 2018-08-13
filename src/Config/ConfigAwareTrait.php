<?php

namespace Amber\Sketch\Config;

use Amber\Config\ConfigAwareTrait as BaseConfig;

trait ConfigAwareTrait
{
    use BaseConfig;

    public function getFolder($folder)
    {
        $raw = $this->getConfig('folders.' . $folder);

        return $this->normalizePath($raw) . DIRECTORY_SEPARATOR;
    }

    public function viewPath($view)
    {
        return $this->getFolder('views') . $view;
    }

    public function layoutPath($layout)
    {
        return $this->getFolder('layouts') . $layout;
    }

    protected function normalizePath($path)
    {
        return str_replace(['/', '\\', '.'], DIRECTORY_SEPARATOR, $path);
    }
}
