<?php

namespace PHPMin\CssMin\Tokens;

/**
 * Abstract definition of a for at-rule block end token.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
abstract class CssAtBlockEndTokenAbstract extends CssTokenAbstract
{
    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "}";
    }
}
