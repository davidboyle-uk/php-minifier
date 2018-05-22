<?php

namespace PHPMin\CssMin\Filters;

/**
 * This {@link CssMinifierFilterAbstract minifier filter}
 * sets the IsLast property of any last declaration in a ruleset,
 * @font-face at-rule or @page at-rule block.
 * If the property IsLast is TRUE the decrations will get stringified
 * without tailing semicolon.
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssRemoveLastDelarationSemiColonMinifierFilter extends CssMinifierFilterAbstract
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
        for ($i = 0, $l = count($tokens); $i < $l; $i++) {
            $current    = get_class($tokens[$i]);
            $next       = isset($tokens[$i+1]) ? get_class($tokens[$i+1]) : false;
            if (($current === "PHPMin\CssMin\Tokens\CssRulesetDeclarationToken" &&
                $next === "PHPMin\CssMin\Tokens\CssRulesetEndToken") ||
                ($current === "PHPMin\CssMin\Tokens\CssAtFontFaceDeclarationToken" &&
                    $next === "PHPMin\CssMin\Tokens\CssAtFontFaceEndToken") ||
                ($current === "PHPMin\CssMin\Tokens\CssAtPageDeclarationToken" &&
                    $next === "PHPMin\CssMin\Tokens\CssAtPageEndToken")
            ) {
                $tokens[$i]->isLast = true;
            }
        }
        
        return 0;
    }
}
