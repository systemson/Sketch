<?php

namespace Amber\Sketch\Config;

trait ConfigAware
{
    public $config = [];

    public function setConfig(array $config)
    {
        foreach($config as $key => $value)
        {
            $this->config[$key] = $value;
        }
    }

    public function getConfig(string $key, $default = null)
    {
        $config = $this->config;

        foreach (explode('.', $key) as $search) {
            if (isset($config[$search])) {
                $config = $config[$search];
            } else {
                return $default;
            }
        }

        return $config;
    }

    public function getFolder($folder)
    {
        $raw = $this->getConfig('folders.'.$folder);

        return $this->normalizePath($raw).DIRECTORY_SEPARATOR;
    }

    public function viewPath($view)
    {
        return $this->getFolder('views').$view;
    }

    public function layoutPath($layout)
    {
        return $this->getFolder('layouts').$layout;
    }

    protected function normalizePath($path)
    {
        return str_replace(['/', '\\', '.'], DIRECTORY_SEPARATOR, $path);
    }
}