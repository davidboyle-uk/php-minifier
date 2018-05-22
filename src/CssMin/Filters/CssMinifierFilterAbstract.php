<?php

namespace PHPMin\CssMin\Filters;

use PHPMin\CssMin\CssMinifier;

/**
 * Abstract definition of a minifier filter class.
 *
 * Minifier filters allows a pre-processing of the parsed token to add, edit or delete tokens. Every minifier filter
 * has to extend this class.
 *
 * @package     CssMin/Minifier/Filters
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
abstract class CssMinifierFilterAbstract
{
    /**
     * Filter configuration.
     *
     * @var array
     */
    protected $configuration = array();

    /**
     * The CssMinifier of the filter.
     *
     * @var CssMinifier
     */
    protected $minifier = null;

    /**
     * Constructor.
     *
     * @param CssMinifier $minifier The CssMinifier object of this plugin.
     * @param array $configuration Filter configuration [optional]
     * @return void
     */
    public function __construct(CssMinifier $minifier, array $configuration = array())
    {
        $this->configuration    = $configuration;
        $this->minifier         = $minifier;
    }

    /**
     * Filter the tokens.
     *
     * @param array $tokens Array of objects of type CssTokenAbstract
     * @return integer Count of added, changed or removed tokens; a return value large than 0 will rebuild the array
     */
    abstract public function apply(array &$tokens);
}
