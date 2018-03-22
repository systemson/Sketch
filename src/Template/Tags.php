<?php

namespace Amber\Sketch\Template;

use Amber\Sketch\Template\Tag;

/**
 * Handle the control structures from the template content.
 */
class Tags
{
    /**
     * @var array The control structures list.
     */
    public static $tags = [
        'if',
        'elseif',
        'else',
        'for',
        'foreach',
        'while',
    ];

    /**
     * Find the control structures from the template content
     *
     * @todo Handle the control structures that do not require a statement.
     *
     * @param string $content The template content.
     * @return object The opening and closing tags.
     */
    public static function find($content)
    {
        $openers = [];
        $closers = [];

        $tags = implode('|', self::$tags);

        /** Find the opening tags from the template content. */
        preg_match_all(
            "'<({$tags})=\"(.*?)\">'si",
            $content,
            $openers
        );

        /** Find the closing tags from the template content. */
        preg_match_all(
            "'</({$tags})>'si",
            $content,
            $closers
        );

        /** Returns the opening and closing tags from the template content */
        return (object) ['openers' => $openers, 'closers' => $closers];
    }

    /**
     * Get the control structures tags and their replaces.
     *
     * @param string $content The template content.
     * @return object The tag matches and the output to replace them.
     */
    public static function get($content)
    {
        /** Get the matched tags from the content */
        $matches = self::find($content);

        /** Get the opening tags */
        $openers = $matches->openers;

        /** Get the closing tags */
        $closers = $matches->closers;

        $output = [];

        /** Construct the opening control structures */
        for($x = 0; $x < count($openers[1]); $x++){
            $output[] = Tag::open($openers[1][$x], $openers[2][$x]);
        }

        /** Construct the closing control structures */
        foreach($closers[1] as $closer){
            $output[] = Tag::close($closer);
        }

        return (object) [
            /** Merge the opening and closing tag matches */
            'tags' => array_merge($openers[0], $closers[0]),

            'output' => $output
        ];
    }
}
