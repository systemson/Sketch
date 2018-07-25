<?php

namespace Amber\Sketch\Plugins;

/**
 * Handle the template blocks.
 */
class Blocks
{
    /**
     * Find the block tags in the content.
     *
     * @param string $content The template content.
     *
     * @return array The matches of the blocks.
     */
    public static function find($content)
    {
        /*
         * Search all block tags on the content.
         *
         * The regex match tags like <block="name">*</block>
         */
        preg_match_all(
            "'<block=\"(.*?)\">(.*?)</block>'si",
            $content,
            $blocks
        );

        return (object) [
            'match'   => $blocks[0],
            'name'    => $blocks[1],
            'content' => $blocks[2],
        ];
    }

    /**
     * Get the content from the blocks.
     *
     * @param string $content The template content.
     *
     * @return object The tag matches and the output to replace the tags
     */
    public static function get($content, array $blocks = [])
    {
        /** Get the blocks from the template content */
        $match = self::find($content);

        /* Iterate each match */
        for ($x = 0; $x < count($match->name); $x++) {
            /* Set/update the blocks */
            $blocks[$match->name[$x]] = str_replace(
                '<parent>',
                $blocks[$match->name[$x]] ?? null,
                $match->content[$x]
            );
        }

        /* Returns the blocks */
        return $blocks;
    }
}
