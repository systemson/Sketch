<?php

namespace Amber\Sketch\Engine;

/**
 * Handle folders in the file system.
 */
class Folder
{
    /**
     * @var string The name of the folder.
     */
    public $name;

    /**
     * @var string
     *             The path to the folder.
     */
    public $path;

    /**
     * Instantiate the folder.
     *
     * @param string $name The name of the folder.
     * @param string $path The path to the folder.
     *
     * @return void
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * Checks if the folder exists.
     *
     * @return bool
     */
    public function exists()
    {
        return Filesystem::has($this->path);
    }

    /**
     * Create the folder.
     *
     * @return void
     */
    public function create()
    {
        if (!$this->exists()) {
            Filesystem::createDir($this->path);
        }
    }

    /**
     * Delete the folder.
     *
     * @return void
     */
    public function delete()
    {
        if ($this->exists()) {
            Filesystem::deleteDir($this->path);
        }
    }
}
