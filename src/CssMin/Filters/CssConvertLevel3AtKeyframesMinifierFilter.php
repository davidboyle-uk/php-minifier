<?php

namespace PHPMin\CssMin\Filters;

/**
 * This {@link CssMinifierFilterAbstract minifier filter}
 * will convert @keyframes at-rule block to browser specific counterparts.
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssConvertLevel3AtKeyframesMinifierFilter extends CssMinifierFilterAbstract
{
    /**
     * Implements {@link CssMinifierFilterAbstract::filter()}.
     *
     * @param array $tokens Array of objects of type CssTokenAbstract
     * @return integer Count of added, changed or removed tokens;
     * a return value larger than 0 will rebuild the array
     */
    public function apply(array &$tokens)
    {
        $r = 0;
        $transformations = array("-moz-keyframes", "-webkit-keyframes");

        for ($i = 0, $l = count($tokens); $i < $l; $i++) {
            if (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssAtKeyframesStartToken") {
                for ($ii = $i; $ii < $l; $ii++) {
                    if (get_class($tokens[$ii]) === "PHPMin\CssMin\Tokens\CssAtKeyframesEndToken") {
                        break;
                    }
                }
                if (get_class($tokens[$ii]) === "PHPMin\CssMin\Tokens\CssAtKeyframesEndToken") {
                    $add    = array();
                    $source = array();

                    for ($iii = $i; $iii <= $ii; $iii++) {
                        $source[] = clone($tokens[$iii]);
                    }
                    foreach ($transformations as $transformation) {
                        $t = array();
                        foreach ($source as $token) {
                            $t[] = clone($token);
                        }
                        $t[0]->atRuleName = $transformation;
                        //dont add if already exists
                        foreach ($tokens as $token) {
                            if ($token == $t[0]) {
                                continue 2;
                            }
                        }
                        $add = array_merge($add, $t);
                    }
                    if (isset($this->configuration["RemoveSource"]) && $this->configuration["RemoveSource"] === true) {
                        array_splice($tokens, $i, $ii - $i + 1, $add);
                    } else {
                        array_splice($tokens, $ii + 1, 0, $add);
                    }
                    $l = count($tokens);
                    $i = $ii + count($add);
                    $r += count($add);
                }
            }
        }

        return $r;
    }
}
