<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents a CSS comment.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssCommentToken extends CssTokenAbstract
{
    /**
     * comment as Text.
     *
     * @var string
     */
    public $comment = "";

    /**
     * Set the properties of a comment token.
     *
     * @param string $comment comment including comment delimiters
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->comment;
    }
}
