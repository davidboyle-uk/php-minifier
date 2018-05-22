<?php

namespace PHPMin\CssMin\Filters;

/**
 * This {@link CssMinifierFilterAbstract minifier filter} will remove any comments from the array of parsed tokens.
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssRemoveCommentsMinifierFilter extends CssMinifierFilterAbstract
{
    /**
     * Implements {@link CssMinifierFilterAbstract::filter()}.
     *
     * @param array $tokens Array of objects of type CssTokenAbstract
     * @return integer Count of added, changed or removed tokens; a return value large than 0 will rebuild the array
     */
    public function apply(array &$tokens)
    {
        $r = 0;
        for ($i = 0, $l = count($tokens); $i < $l; $i++) {
            if (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssCommentToken") {
                $tokens[$i] = null;
                $r++;
            }
        }

        return $r;
    }
}
