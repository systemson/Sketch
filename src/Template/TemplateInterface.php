<?php

namespace Amber\Sketch\Template;

interface TemplateInterface
{
    /**
     * Sets the template view.
     *
     * @param string $path The path to the view.
     *
     * @return void
     */
    public function setView($name);

    /**
     * Sets the template layout.
     *
     * @param $name The layout name.
     *
     * @return void
     */
    public function setLayout($name);

    /**
     * Gets the timestamp of the newer file.
     *
     * @todo Add the timestamp from the includes.
     *
     * @return int The max timestamp from the View and the Layout.
     */
    public function timestamp();

    /**
     * Outputs the template final content.
     *
     * @return string The updated final content.
     */
    public function output();

    /**
     * Returns a new File instance for the cache file.
     *
     * @return string The updated final content.
     */
    public function cache();
}
