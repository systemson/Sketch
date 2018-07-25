<?php

namespace Amber\Sketch\Plugins;

class TagHandler implements TagInterface
{
    public $name;

    /**
     * Sets the opening tag for this.
     */
    public function opening()
    {
        return $this->name;
    }

    /**
     * Sets the arguments that this tags should accept.
     */
    public function arguments()
    {
        return [
            //
        ];
    }

    /**
     * Returns the content after being procesed.
     */
    public function content(string $content)
    {
        return $content;
    }

    /**
     * Sets the closing tag for this.
     *
     * If it is a self closing tag this method MUST return void or null.
     */
    public function closing()
    {
        return null;
    }
}
