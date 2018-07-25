<?php

namespace Amber\Sketch\Plugins;

interface TagInterface
{
    /**
     * Sets the opening tag for this.
     */
    public function opening();

    /**
     * Sets the arguments that this tags should accept.
     *
     * @return array An array naming arguments the
                     template must search inside the current tag.
     */
    public function arguments();

    /**
     * Returns the content after being procesed.
     *
     * @param string the content between the opening and closing tag.
     *
     * @return string The content after
     */
    public function content(string $content);

    /**
     * Sets the closing tag for this.
     *
     * If it is a self closing tag this method MUST return void or null.
     */
    public function closing();
}
