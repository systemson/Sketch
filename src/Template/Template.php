<?php

namespace Amber\Sketch\Template;

use Amber\Filesystem\File;
use Amber\Filesystem\Directories;

/**
 * Handle the template request.
 *
 * @todo Get the newer file so the compiler can check if the cache file need to be remade.
 */
class Template
{
    /**
     * @var The template view.
     */
    public $view;

    /**
     * @var The template layout.
     */
    public $layout;

    /**
     * @var The template cache name.
     */
    public $cacheName;

    /**
     * @var The template blocks.
     */
    public $blocks = [];

    /**
     * @var The template include files.
     */
    public $includes = [];

    /**
     * Instantiate the Template.
     *
     * @param string $view The template view.
     * @param string $layout The template layout.
     *
     * @return void
     */
    public function __construct($view, $layout)
    {
        /* Set the view name */
        $this->setView($view);

        /* Set the layout name */
        $this->setLayout($layout);

        /* Get the blocks from the layout */
        $this->blocks($this->layout->content, $this->blocks);

        /* Get the blocks from the view */
        $this->blocks($this->view->content, $this->blocks);
    }

    /**
     * Set the template view.
     *
     * @param string $path The path to the view.
     *
     * @return void
     */
    public function setView($path)
    {
        $this->view = new File(Directories::directories('views').$path);
        $this->cacheName = Directories::directories('cache').sha1($this->view->name).'.php';
    }

    /**
     * Set the template layout.
     *
     * @param $name The layout name.
     *
     * @return void
     */
    public function setLayout($name)
    {
        $this->layout = new File(Directories::directories('layouts').$name);
    }

    /**
     * Put the view into the layout.
     *
     * @return string The Layout content with the view content.
     */
    public function putView()
    {
        /* Replace the view tag (<view>) with the view content */
        return str_replace(
            '<view>',
            $this->view->content,
            $this->layout->content
        );
    }

    /**
     * Find and store the block from the template content.
     *
     * @param string $content The template content.
     * @param array  $blocks  The template blocks.
     *
     * @return array The updated template blocks.
     */
    public function blocks($content, array $blocks = [])
    {
        return $this->blocks = Blocks::get($content, $blocks);
    }

    /**
     * Replace the block tags with the block content.
     *
     * @param string $content The template content.
     *
     * @return string The updated template content
     */
    public function blockOutput($content)
    {
        foreach ($this->blocks as $key => $value) {
            $content = preg_replace(
                "'<block=\"{$key}\">(.*?)</block>'si",
                $value,
                $content
            );
        }

        return $content;
    }

    /**
     * Replace the include tags with the include files content.
     *
     * @param string $content The template content.
     *
     * @return string The updated template content.
     */
    public function includes($content)
    {
        $includes = Includes::get($content);

        return str_replace(
            $includes->tags,
            array_column($includes->files, 'content'),
            $content
        );
    }

    /**
     * Replace the control structures tags.
     *
     * @param string $content The template content.
     *
     * @return string The updated template content.
     */
    public function tags($content)
    {
        $tags = Tags::get($content);

        return str_replace(
            $tags->tags,
            $tags->output,
            $content
        );
    }

    /**
     * Get the timestamp of the newer file.
     *
     * @todo Add the timestamp from the includes.
     *
     * @return int The max timestamp from the View and the Layout.
     */
    public function timestamp()
    {
        /* Set the layout and view timestamp */
        return $this->timestamp = max(
            //$this->timestamp,
            $this->view->timestamp,
            $this->layout->timestamp
        );
    }

    /**
     * Output the template final content.
     *
     * @return string The updated final content.
     */
    public function output()
    {
        $content = $this->blockOutput($this->putView());

        $content = $this->includes($content);

        $content = $this->tags($content);

        return $content;
    }
}
