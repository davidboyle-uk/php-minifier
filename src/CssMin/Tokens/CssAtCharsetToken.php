<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents a @charset at-rule.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtCharsetToken extends CssTokenAbstract
{
    /**
     * charset of the @charset at-rule.
     *
     * @var string
     */
    public $charset = "";

    /**
     * Set the properties of @charset at-rule token.
     *
     * @param string $charset charset of the @charset at-rule token
     * @return void
     */
    public function __construct($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "@charset " . $this->charset . ";";
    }
}
