<?php

namespace PHPMin\CssMin\Filters;

/**
 * This {@link CssMinifierFilterAbstract minifier filter}
 * will remove any empty rulesets (including @keyframes at-rule block
 * rulesets).
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssRemoveEmptyRulesetsMinifierFilter extends CssMinifierFilterAbstract
{
    /**
     * Implements {@link CssMinifierFilterAbstract::filter()}.
     *
     * @param array $tokens Array of objects of type CssTokenAbstract
     * @return integer Count of added, changed or removed tokens;
     * a return value large than 0 will rebuild the array
     */
    public function apply(array &$tokens)
    {
        $r = 0;

        for ($i = 0, $l = count($tokens); $i < $l; $i++) {
            $current    = get_class($tokens[$i]);
            $next       = isset($tokens[$i + 1]) ? get_class($tokens[$i + 1]) : false;

            if (($current === "PHPMin\CssMin\Tokens\CssRulesetStartToken"
                && $next === "PHPMin\CssMin\Tokens\CssRulesetEndToken"
            ) || (
                    $current === "PHPMin\CssMin\Tokens\CssAtKeyframesRulesetStartToken"
                    && $next === "PHPMin\CssMin\Tokens\CssAtKeyframesRulesetEndToken"
                    && !array_intersect(
                        array("from", "0%", "to", "100%"),
                        array_map("strtolower", $tokens[$i]->selectors)
                    )
                )
            ) {
                $tokens[$i]     = null;
                $tokens[$i + 1] = null;
                $i++;
                $r = $r + 2;
            }
        }

        return $r;
    }
}
