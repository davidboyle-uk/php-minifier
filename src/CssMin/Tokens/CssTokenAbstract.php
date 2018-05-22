<?php

namespace PHPMin\CssMin\Tokens;

/**
 * Abstract definition of a CSS token class.
 *
 * Every token has to extend this class.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
abstract class CssTokenAbstract
{
    /**
     * Returns the token as string.
     *
     * @return string
     */
    abstract public function __toString();
}
