<?php

namespace Amber\Sketch\Config;

class Config
{
    public static $config = [];

    public static function init($config = []) {

        self::$config = include __DIR__.DIRECTORY_SEPARATOR.'default.php';
    }

    public static function get($config)
    {
        $search = explode('.', $config);

        if (empty(self::$config)) {
            self::init();
        }

        $config = self::$config;

        foreach ($search as $search) {
            $config = $config[$search];
        }

        return $config;

    }

    public static function folder($config)
    {
        return Config::get('folders.'.$config).DIRECTORY_SEPARATOR;

    }
}
