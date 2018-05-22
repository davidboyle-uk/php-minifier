<?php

namespace PHPMin\CssMin\Tokens;

/**
 * Abstract definition of a ruleset declaration token.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
abstract class CssDeclarationTokenAbstract extends CssTokenAbstract
{
    /**
     * is the declaration flagged as important?
     *
     * @var boolean
     */
    public $isImportant = false;

    /**
     * is the declaration flagged as last one of the ruleset?
     *
     * @var boolean
     */
    public $isLast = false;

    /**
     * property name of the declaration.
     *
     * @var string
     */
    public $property = "";

    /**
     * value of the declaration.
     *
     * @var string
     */
    public $value = "";

    /**
     * Set the properties of the @font-face declaration.
     *
     * @param string $property property of the declaration
     * @param string $value value of the declaration
     * @param boolean $isImportant is the !important flag is set?
     * @param boolean $IsLast is the declaration the last one of the block?
     * @return void
     */
    public function __construct($property, $value, $isImportant = false, $isLast = false)
    {
        $this->property     = $property;
        $this->value        = $value;
        $this->isImportant  = $isImportant;
        $this->isLast       = $isLast;
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->property
        . ":"
        . $this->value
        . ($this->isImportant ? " !important" : "")
        . ($this->isLast ? "" : ";");
    }
}
