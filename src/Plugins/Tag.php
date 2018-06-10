<?php

namespace Amber\Sketch\Plugins;

/**
 * The control structures tag.
 */
class Tag
{
    /**
     * Open the control structure.
     *
     * @param string $name      The name of the control structure.
     * @param string $statement The statement of the control structure.
     *
     * @return string The opening for control structure.
     */
    public static function open($name, $statement = null)
    {
        /** Name the control structure */
        $tag = "<?php {$name}";

        /* If a statement if passed set the statement */
        $tag .= $statement ? "({$statement}):" : null;

        /* add the closing php tag */
        $tag .= ' ?>';

        return $tag;
    }

    /**
     * Close the control structure.
     *
     * @param string $name The name of the control structure.
     *
     * @return string The closing for control structure.
     */
    public static function close($name)
    {
        return "<?php end{$name}; ?>";
    }
}
