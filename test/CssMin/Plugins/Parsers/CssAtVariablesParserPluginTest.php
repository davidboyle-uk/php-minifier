<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssAtVariablesParserPlugin;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssAtVariablesParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtVariablesParserPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Parsers';

    /* Plugin Configuration */
    protected $plugins = [
        "Comment"                       => false,
        "String"                        => false,
        "Url"                           => false,
        "Expression"                    => false,
        "Ruleset"                       => false,
        "AtCharset"                     => false,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => true
    ];

    public function testCssAtVariablesParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssAtVariablesParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssAtVariablesParserPlugin-parse-out.ser';

        $testInput = file_get_contents($inputFile);
        $expected = unserialize(file_get_contents($outputFile));

        $minifier = new CssMin();
        $minifier->setVerbose(true);
        
        $actual = $minifier->parse(
            $testInput,
            $this->plugins
        );

        $this->assertSame(serialize($expected), serialize($actual));
    }

    public function testCssAtVariablesParserPluginClosingBraceParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtVariablesParserPlugin($parser);

        $index = 0;
        $char = '}';
        $previousChar = '.';
        $state = "T_AT_VARIABLES_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssAtVariablesParserPluginThrowParse()
    {
        $expected = true;

        $parser = new CssParser();
        $parser->setBuffer('filter');
        $plugin = new CssAtVariablesParserPlugin($parser);

        $index = 0;
        $char = ':';
        $previousChar = '.';
        $state = "T_AT_VARIABLES_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtVariablesParserPluginBufferParse()
    {
        $expected = false;

        $parser = new CssParser();
        $parser->setBuffer('filter');
        $plugin = new CssAtVariablesParserPlugin($parser);

        $index = 0;
        $char = ':';
        $previousChar = '.';
        
        $state = "T_AT_VARIABLES";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_AT_VARIABLES_DECLARATION";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}
