<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssAtImportParserPlugin;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssAtImportParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtImportParserPluginTest extends TestCase
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
        "AtImport"                      => true,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];

    public function testCssAtImportParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssAtImportParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssAtImportParserPlugin-parse-out.ser';

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

    public function testCssAtImportParserPluginAtParse()
    {
        $expected = false;

        $parser = new CssParser();
        $plugin = new CssAtImportParserPlugin($parser);

        $index = 0;
        $char = '@';
        $previousChar = '.';
        $state = "T_DOCUMENT";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssAtImportParserPluginSemiColonParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtImportParserPlugin($parser);

        $index = 20;
        $char = ';';
        $previousChar = '.';
        $state = "T_AT_IMPORT";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}
