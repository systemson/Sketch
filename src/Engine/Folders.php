<?php

namespace Amber\Sketch\Engine;

/**
 * Folder lists container.
 */
class Folders
{
    /**
     * @var array The folder names and their paths.
     */
    public static $folders = [
        'views'    => '/app/views/',
        'layouts'  => '/app/views/layouts/',
        'includes' => '/app/views/includes/',
        'cache'    => '/tmp/views/',
    ];

    /**
     * Get or set the folder.
     *
     * @param string $name The name of the folder.
     * @param string $path The path of the folder.
     *
     * @return mixed
     */
    public static function folder($name, $path = null)
    {
        if ($path != null) {

            /* If the path is passed create a new Folder object */
            return self::$folders[$name] = new Folder($name, $path);
        } else {

            /* If no path is passed return the folder object */
            return self::$folders[$name] ?? null;
        }
    }
}
