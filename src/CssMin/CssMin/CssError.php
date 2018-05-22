<?php

namespace PHPMin\CssMin;

use PHPMin\CssMin;

/**
 * CSS Error.
 *
 * @package     CssMin
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssError
{
    /**
     * file.
     *
     * @var string
     */
    public $file = "";

    /**
     * line.
     *
     * @var integer
     */
    public $line = 0;

    /**
     * Error message.
     *
     * @var string
     */
    public $message = "";

    /**
     * Source.
     *
     * @var string
     */
    public $source = "";

    /**
     * Constructor triggering the error.
     *
     * @param string $message Error message
     * @param string $source Corresponding line [optional]
     * @return void
     */
    public function __construct($file, $line, $message, $source = "")
    {
        $this->file     = $file;
        $this->line     = $line;
        $this->message  = $message;
        $this->source   = $source;
    }

    /**
     * Returns the error as formatted string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->message
            . ($this->source ? ": <br /><code>"
            . $this->source
            . "</code>": "")
            . "<br />in file "
            . $this->file
            . " at line "
            . $this->line;
    }
}
