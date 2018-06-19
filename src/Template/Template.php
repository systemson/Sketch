<?php

namespace Amber\Sketch\Template;

use Amber\Filesystem\File;

/**
 * Handle the template request.
 */
class Template implements TemplateInterface
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
     * @var The template file content.
     */
    public $content;

    /**
     * @var The template data.
     */
    public $data = [];

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
     * @param string $view   The template view.
     * @param string $layout The template layout.
     * @param string $data   The template data.
     *
     * @return void
     */
    public function __construct($view = null, $layout = null, $data = [])
    {
        /* Set the view name. */
        $this->setView($view);

        /* Set the layout name. */
        $this->setLayout($layout);

        /* Set the template data. */
        $this->setData($data);
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
        $this->view = new File($path);

        if (!$this->view->exists()) {
            throw new \Exception("File {$this->view->getFullPath()} does not exists");
        }

        $this->cacheName = sha1($path);
    }

    /**
     * Set the template layout.
     *
     * @param $name The layout name.
     *
     * @return void
     */
    public function setLayout($path)
    {
        $this->layout = new File($path);

        if (!$this->layout->exists()) {
            throw new \Exception("File {$this->layout->getFullPath()} does not exists");
        }
    }

    /**
     * Set the template data.
     *
     * @param $data The template data.
     *
     * @return void
     */
    public function setData($data = [])
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function getData()
    {
        return $this->data;
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
            '<sketch>',
            $this->view->getContent(),
            $this->layout->getContent()
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
    /*public function blocks($content, array $blocks = []) : array
    {
        return $this->blocks = Blocks::get($content, $blocks);
    }*/

    /**
     * Replace the block tags with the block content.
     *
     * @param string $content The template content.
     *
     * @return string The updated template content
     */
    /*public function blockOutput($content)
    {
        foreach ($this->blocks as $key => $value) {
            $content = preg_replace(
                "'<block=\"{$key}\">(.*?)</block>'si",
                $value,
                $content
            );
        }

        return $content;
    }*/

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
    /*public function tags($content)
    {
        $tags = Tags::get($content);

        return str_replace(
            $tags->tags,
            $tags->output,
            $content
        );
    }*/

    /**
     * Get the timestamp of the newer file.
     *
     * @todo Add the timestamp from the includes.
     *
     * @return int The max timestamp from the View and the Layout.
     */
    public function getTimestamp()
    {
        /* Set the layout and view timestamp */
        return max(
            $this->view->getTimestamp(),
            $this->layout->getTimestamp()
        );
    }

    /**
     * Output the template final content.
     *
     * @return string The updated final content.
     */
    public function output()
    {
        $content = $this->putView();

        $content = $this->includes($content);

        //$content = $this->tags($content);*/

        return $content;
    }

    /**
     * Returns a new Template instance for the cache file.
     *
     * @return string The updated final content.
     */
    public function cache($basepath = null)
    {
        return new File($basepath.sha1($this->view->getPath()), $this->output());
    }
}
