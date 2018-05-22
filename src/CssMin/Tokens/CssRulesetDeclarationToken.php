<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents a ruleset declaration.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssRulesetDeclarationToken extends CssDeclarationTokenAbstract
{
    /**
     * media types of the declaration.
     *
     * @var array
     */
    public $mediaTypes = array("all");

    /**
     * Set the properties of a ddocument- or at-rule @media level declaration.
     *
     * @param string $property property of the declaration
     * @param string $value value of the declaration
     * @param mixed $mediaTypes media types of the declaration
     * @param boolean $isImportant is the !important flag is set
     * @param boolean $isLast is the declaration the last one of the ruleset
     * @return void
     */
    public function __construct($property, $value, $mediaTypes = null, $isImportant = false, $isLast = false)
    {
        parent::__construct($property, $value, $isImportant, $isLast);
        $this->mediaTypes   = $mediaTypes ? $mediaTypes : array("all");
    }
}
