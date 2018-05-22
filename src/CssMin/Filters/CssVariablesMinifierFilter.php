<?php

namespace PHPMin\CssMin\Filters;

use PHPMin\CssMin;
use PHPMin\CssMin\CssError;

/**
 * This {@link CssMinifierFilterAbstract minifier filter}
 * will parse the variable declarations out of @variables at-rule blocks.
 * The variables will get store in the {@link CssVariablesMinifierPlugin}
 * that will apply the variables to declaration.
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssVariablesMinifierFilter extends CssMinifierFilterAbstract
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
        $variables          = array();
        $defaultMediaTypes  = array("all");
        $mediaTypes         = array();
        $remove             = array();

        for ($i = 0, $l = count($tokens); $i < $l; $i++) {
            // @variables at-rule block found
            if (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssAtVariablesStartToken") {
                $remove[] = $i;
                $mediaTypes = (count($tokens[$i]->mediaTypes) == 0 ? $defaultMediaTypes : $tokens[$i]->mediaTypes);

                foreach ($mediaTypes as $mediaType) {
                    if (!isset($variables[$mediaType])) {
                        $variables[$mediaType] = array();
                    }
                }

                // Read the variable declaration tokens
                for ($i = $i; $i < $l; $i++) {
                    // Found a variable declaration => read the variable values
                    if (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssAtVariablesDeclarationToken") {
                        foreach ($mediaTypes as $mediaType) {
                            $variables[$mediaType][$tokens[$i]->property] = $tokens[$i]->value;
                        }
                        $remove[] = $i;
                    } // Found the variables end token => break;
                    elseif (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssAtVariablesEndToken") {
                        $remove[] = $i;
                        break;
                    }
                }
            }
            // css ruleset variable declaration prefix -- eg. --main-bg-color
            if (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssRulesetDeclarationToken") {
                if (strpos($tokens[$i]->property, '--') === 0) {
                    foreach ($tokens[$i]->mediaTypes as $mediaType) {
                        $variables[$mediaType][$tokens[$i]->property] = $tokens[$i]->value;
                    }
                    $remove[] = $i;
                }
            }
        }

        // Variables in @variables at-rule blocks
        foreach ($variables as $mediaType => $null) {
            foreach ($variables[$mediaType] as $variable => $value) {
                // If a var() statement in a variable value found...
                if (stripos($value, "var") !== false && preg_match_all("/var\((.+)\)/iSU", $value, $m)) {
                    // ... then replace the var() statement with the variable values.
                    for ($i = 0, $l = count($m[0]); $i < $l; $i++) {
                        $variables[$mediaType][$variable] = str_replace(
                            $m[0][$i],
                            (isset($variables[$mediaType][$m[1][$i]]) ? $variables[$mediaType][$m[1][$i]] : ""),
                            $variables[$mediaType][$variable]
                        );
                    }
                }
            }
        }

        // Remove the complete @variables at-rule block
        foreach ($remove as $i) {
            $tokens[$i] = null;
        }

        if (!($plugin = $this->minifier->getPlugin("PHPMin\CssMin\Plugins\Minifiers\CssVariablesMinifierPlugin"))) {
            CssMin::triggerError(
                new CssError(
                    __FILE__,
                    __LINE__,
                    __METHOD__
                    . ": The plugin <code>PHPMin\CssMin\Plugins\Minifiers\CssVariablesMinifierPlugin</code> "
                    . "was not found but is required for <code>"
                    . __CLASS__
                    . "</code>"
                )
            );
        } else {
            $plugin->setVariables($variables);
        }

        return count($remove);
    }
}
