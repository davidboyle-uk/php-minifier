<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Tokens\CssTokenAbstract;

/**
 * Abstract definition of a minifier plugin class.
 *
 * Minifier plugin process the parsed tokens one by one to apply changes to the token. Every minifier plugin has to
 * extend this class.
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
abstract class CssMinifierPluginAbstract
{
    /**
     * Plugin configuration.
     *
     * @var array
     */
    protected $configuration = array();

    /**
     * The CssMinifier of the plugin.
     *
     * @var CssMinifier
     */
    protected $minifier = null;

    /**
     * Constructor.
     *
     * @param CssMinifier $minifier The CssMinifier object of this plugin.
     * @param array $configuration Plugin configuration [optional]
     * @return void
     */
    public function __construct(CssMinifier $minifier, array $configuration = array())
    {
        $this->configuration    = $configuration;
        $this->minifier         = $minifier;
    }

    /**
     * Apply the plugin to the token.
     *
     * @param CssTokenAbstract $token Token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    abstract public function apply(CssTokenAbstract &$token);

    /**
     * --
     *
     * @return array
     */
    abstract public function getTriggerTokens();
}
