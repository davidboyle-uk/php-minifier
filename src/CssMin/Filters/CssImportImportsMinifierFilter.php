<?php

namespace PHPMin\CssMin\Filters;

use PHPMin\CssMin;
use PHPMin\CssMin\CssError;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Tokens\CssAtMediaStartToken;
use PHPMin\CssMin\Tokens\CssAtMediaEndToken;

/**
 * This {@link CssMinifierFilterAbstract minifier filter}
 * import external css files defined with the @import at-rule into the
 * current stylesheet.
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssImportImportsMinifierFilter extends CssMinifierFilterAbstract
{
    /**
     * Array with already imported external stylesheets.
     *
     * @var array
     */
    protected $imported = array();

    /**
     * Implements {@link CssMinifierFilterAbstract::filter()}.
     *
     * @param array $tokens Array of objects of type CssTokenAbstract
     * @return integer Count of added, changed or removed tokens;
     * a return value large than 0 will rebuild the array
     */
    public function apply(array &$tokens)
    {
        if (!isset($this->configuration["BasePath"]) || !is_dir($this->configuration["BasePath"])) {
            CssMin::triggerError(
                new CssError(
                    __FILE__,
                    __LINE__,
                    __METHOD__
                    . ": Base path <code>"
                    . (isset($this->configuration["BasePath"]) ? $this->configuration["BasePath"] : "null")
                    . "</code> is not a directory"
                )
            );
            return 0;
        }

        for ($i = 0, $l = count($tokens); $i < $l; $i++) {
            if (get_class($tokens[$i]) === "PHPMin\CssMin\Tokens\CssAtImportToken") {
                $import = $this->configuration["BasePath"] . "/" . $tokens[$i]->import;

                // Import file was not found/is not a file
                if (!is_file($import)) {
                    CssMin::triggerError(
                        new CssError(
                            __FILE__,
                            __LINE__,
                            __METHOD__
                            . ": Import file <code>"
                            . $import
                            . "</code> was not found.",
                            (string) $tokens[$i]
                        )
                    );
                } // Import file already imported; remove this @import at-rule to prevent recursions
                elseif (in_array($import, $this->imported)) {
                    CssMin::triggerError(
                        new CssError(
                            __FILE__,
                            __LINE__,
                            __METHOD__
                            . ": Import file <code>"
                            . $import
                            . "</code> was already imported.",
                            (string) $tokens[$i]
                        )
                    );
                    $tokens[$i] = null;
                } else {
                    $this->imported[] = $import;
                    $parser = new CssParser(file_get_contents($import));
                    $import = $parser->getTokens();

                    // The @import at-rule has media types defined requiring special handling
                    if (count($tokens[$i]->mediaTypes) > 0
                        && !(count($tokens[$i]->mediaTypes) == 1
                        && $tokens[$i]->mediaTypes[0] == "all")
                    ) {
                        $blocks = array();

                        /*
                         * Filter or set media types of @import at-rule or remove the
                         * @import at-rule if no media type is matching the parent @import at-rule
                         */
                        for ($ii = 0, $ll = count($import); $ii < $ll; $ii++) {
                            if (get_class($import[$ii]) === "PHPMin\CssMin\Tokens\CssAtImportToken") {
                                // @import at-rule defines no media type or only the "all" media type;
                                // set the media types to the one defined in the parent @import at-rule
                                if (count($import[$ii]->mediaTypes) == 0
                                    || (count($import[$ii]->mediaTypes) == 1
                                    && $import[$ii]->mediaTypes[0] == "all")
                                ) {
                                    $import[$ii]->mediaTypes = $tokens[$i]->mediaTypes;
                                } // @import at-rule defineds one or more media types;
                                // filter out media types not matching with the  parent @import at-rule
                                elseif (count($import[$ii]->mediaTypes > 0)) {
                                    foreach ($import[$ii]->mediaTypes as $index => $mediaType) {
                                        if (!in_array($mediaType, $tokens[$i]->mediaTypes)) {
                                            unset($import[$ii]->mediaTypes[$index]);
                                        }
                                    }
                                    $import[$ii]->mediaTypes = array_values($import[$ii]->mediaTypes);
                                    // If there are no media types left in the
                                    // @import at-rule remove the @import at-rule
                                    if (count($import[$ii]->mediaTypes) == 0) {
                                        $import[$ii] = null;
                                    }
                                }
                            }
                        }

                        /*
                         * Remove media types of @media at-rule block not defined in the @import at-rule
                         */
                        for ($ii=0, $ll=count($import); $ii<$ll; $ii++) {
                            if (get_class($import[$ii]) === "PHPMin\CssMin\Tokens\CssAtMediaStartToken") {
                                foreach ($import[$ii]->mediaTypes as $index => $mediaType) {
                                    if (!in_array($mediaType, $tokens[$i]->mediaTypes)) {
                                        unset($import[$ii]->mediaTypes[$index]);
                                    }
                                    $import[$ii]->mediaTypes = array_values($import[$ii]->mediaTypes);
                                }
                            }
                        }

                        /*
                         * If no media types left of the @media at-rule block remove the complete block
                         */
                        for ($ii=0, $ll=count($import); $ii<$ll; $ii++) {
                            if (get_class($import[$ii]) === "PHPMin\CssMin\Tokens\CssAtMediaStartToken") {
                                if (count($import[$ii]->mediaTypes) === 0) {
                                    for ($iii = $ii; $iii < $ll; $iii++) {
                                        if (get_class($import[$iii]) === "PHPMin\CssMin\Tokens\CssAtMediaEndToken") {
                                            break;
                                        }
                                    }
                                    if (get_class($import[$iii]) === "PHPMin\CssMin\Tokens\CssAtMediaEndToken") {
                                        array_splice($import, $ii, $iii - $ii + 1, array());
                                        $ll = count($import);
                                    }
                                }
                            }
                        }

                        /*
                         * If the media types of the @media at-rule equals the media types defined in the @import
                         * at-rule remove the CssAtMediaStartToken and CssAtMediaEndToken token
                         */
                        for ($ii=0, $ll=count($import); $ii<$ll; $ii++) {
                            if (get_class($import[$ii]) === "PHPMin\CssMin\Tokens\CssAtMediaStartToken"
                                && count(array_diff($tokens[$i]->mediaTypes, $import[$ii]->mediaTypes)) === 0
                            ) {
                                for ($iii=$ii; $iii<$ll; $iii++) {
                                    if (get_class($import[$iii]) == "PHPMin\CssMin\Tokens\CssAtMediaEndToken") {
                                        break;
                                    }
                                }
                                if (get_class($import[$iii]) == "PHPMin\CssMin\Tokens\CssAtMediaEndToken") {
                                    unset($import[$ii]);
                                    unset($import[$iii]);
                                    $import = array_values($import);
                                    $ll = count($import);
                                }
                            }
                        }

                        /**
                         * Extract CssAtImportToken and CssAtCharsetToken tokens
                         */
                        for ($ii=0, $ll=count($import); $ii<$ll; $ii++) {
                            $class = get_class($import[$ii]);
                            if ($class === "PHPMin\CssMin\Tokens\CssAtImportToken"
                                || $class === "PHPMin\CssMin\Tokens\CssAtCharsetToken"
                            ) {
                                $blocks = array_merge($blocks, array_splice($import, $ii, 1, array()));
                                $ll = count($import);
                            }
                        }

                        /*
                         * Extract the @font-face, @media and @page at-rule block
                         */
                        for ($ii=0, $ll=count($import); $ii<$ll; $ii++) {
                            $class = get_class($import[$ii]);
                            if ($class === "PHPMin\CssMin\Tokens\CssAtMediaStartToken"
                                || $class === "PHPMin\CssMin\Tokens\CssAtPageStartToken"
                                || $class === "PHPMin\CssMin\Tokens\CssAtFontFaceStartToken"
                                || $class === "PHPMin\CssMin\Tokens\CssAtVariablesStartToken"
                            ) {
                                for ($iii=$ii; $iii<$ll; $iii++) {
                                    $class = get_class($import[$iii]);
                                    if ($class === "PHPMin\CssMin\Tokens\CssAtMediaEndToken"
                                        || $class === "PHPMin\CssMin\Tokens\CssAtPageEndToken"
                                        || $class === "PHPMin\CssMin\Tokens\CssAtFontFaceEndToken"
                                        || $class === "PHPMin\CssMin\Tokens\CssAtVariablesEndToken"
                                    ) {
                                        break;
                                    }
                                }
                                $class = get_class($import[$iii]);
                                if (isset($import[$iii])
                                    && ($class === "PHPMin\CssMin\Tokens\CssAtMediaEndToken"
                                        || $class === "PHPMin\CssMin\Tokens\CssAtPageEndToken"
                                        || $class === "PHPMin\CssMin\Tokens\CssAtFontFaceEndToken"
                                        || $class === "PHPMin\CssMin\Tokens\CssAtVariablesEndToken"
                                    )
                                ) {
                                    $blocks = array_merge($blocks, array_splice($import, $ii, $iii - $ii + 1, array()));
                                    $ll = count($import);
                                }
                            }
                        }

                        // Create the import array with extracted tokens
                        // and the rulesets wrapped into a @media at-rule block
                        $import = array_merge(
                            $blocks,
                            array(new CssAtMediaStartToken($tokens[$i]->mediaTypes)),
                            $import,
                            array(new CssAtMediaEndToken())
                        );
                    }

                    // Insert the imported tokens
                    array_splice($tokens, $i, 1, $import);

                    // Modify parameters of the for-loop
                    $i--;
                    $l = count($tokens);
                }
            }
        }

        return $l;
    }
}
