<?php

namespace Amber\Sketch\Template;

use Amber\Filesystem\File;
use Amber\Sketch\Config\Config;

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
                Config::folder('includes') . str_replace('.', '/', $name) . '.php'
            );
        }

        return (object) [
            'tags'  => $includes->match,
            'files' => $files,
        ];
    }
}
