<?php

namespace Amber\Sketch\Engine;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Local;
use Carbon\Carbon;

/**
 * A static singleton like implementation of the League/Flysystem class.
 */
class Filesystem
{
    protected static $instance;

    private function __construct() {}

    /**
     * Singleton implementation.
     */
    public static function getInstance()
    {
        /** Checks if the League/Flysystem is already instantiated. */
        if (!self::$instance instanceof Flysystem)
        {

            /** Local instance */
            $local = new Local(config('app.local_dir'));

            /** Instantiate the League/Flysystem class */
            self::$instance = new Flysystem($local);
        }

        /** Return the instance of League/Flysystem */
        return self::$instance;
    }

    public static function has($path)
    {
        return self::getInstance()->has($path);
    }

    public static function put($path, $content = null)
    {
        return self::getInstance()->put($path, $content);
    }

    public static function read($path)
    {
        return self::getInstance()->read($path);
    }

    public static function delete($path)
    {
        return self::getInstance()->delete($path);
    }

    public static function rename($path, $new_path)
    {
        return self::getInstance()->rename($path, $new_path);
    }

    public static function getMimetype($path)
    {
        return self::getInstance()->getMimetype($path);
    }

    public static function getTimestamp($path)
    {
        return self::getInstance()->getTimestamp($path);
    }

    public static function getLastUpdate($path)
    {
        return Carbon::createFromTimestamp(
            self::getInstance()->getTimestamp($path)
        )->format('Y-m-d H:m:s');
    }

    public static function getSize($path)
    {
        return self::getInstance()->getSize($path);
    }

    public static function createDir($path)
    {
        return self::getInstance()->createDir($path);
    }

    public static function deleteDir($path)
    {
        return self::getInstance()->deleteDir($path);
    }
}