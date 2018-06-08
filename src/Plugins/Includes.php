<?php

namespace Amber\Sketch\Plugins;

use Amber\Filesystem\Directories;
use Amber\Filesystem\File;

/**
 * Handle the template includes.
 */
class Includes
{
    /**
     * Find the include tags in the content.
     *
     * @param string $content The template content.
     *
     * @return array The matches of the includes
     */
    public static function find($content)
    {
        /*
         * Search all include tags on the content
         *
         * The regex match tags like <include="file">
         */
        preg_match_all(
            "'<include=\"(.*?)\">'",
            $content,
            $includes
        );

        return (object) [
            'match' => $includes[0],
            'name'  => $includes[1],
        ];
    }

    /**
     * Get the content from the include files.
     *
     * @param string $content The template content
     *
     * @return object The tag matches and the output to replace the tags
     */
    public static function get($content)
    {
        /** Get the include tags from the template file */
        $includes = self::find($content);

        $files = [];

        /* For each include tag */
        foreach ($includes->name as $name) {

            /* Add the includes files */
            $files[] = new File(
                Directories::directories('includes').str_replace('.', '/', $name)
            );
        }

        return (object) [
            'tags'  => $includes->match,
            'files' => $files,
        ];
    }
}
