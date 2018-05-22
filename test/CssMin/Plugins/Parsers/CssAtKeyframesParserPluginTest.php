<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssAtKeyframesParserPlugin;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssAtKeyframesParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtKeyframesParserPluginTest extends TestCase
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
        "AtKeyframes"                   => true,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];

    public function testCssAtKeyframesParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssAtKeyframesParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssAtKeyframesParserPlugin-parse-out.ser';

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
        $plugin = new CssAtKeyframesParserPlugin($parser);

        $index = 0;
        $char = '@';
        $previousChar = '.';
        $state = "T_DOCUMENT";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtImportParserPluginCommaParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtKeyframesParserPlugin($parser);

        $index = 16;
        $char = ',';
        $previousChar = '.';
        $state = "T_AT_KEYFRAMES_RULESETS";

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
        $plugin = new CssAtKeyframesParserPlugin($parser);

        $index = 16;
        $char = ':';
        $previousChar = '.';
        $state = "T_AT_KEYFRAMES_RULESET_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtImportParserPluginBufferParse()
    {
        $expected = false;

        $parser = new CssParser();
        $parser->setBuffer('filter');
        $plugin = new CssAtKeyframesParserPlugin($parser);

        $index = 16;
        $char = ':';
        $previousChar = '.';

        $state = "T_AT_KEYFRAMES_RULESET";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_AT_KEYFRAMES_RULESET_DECLARATION";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtImportParserPluginClosingBraceParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtKeyframesParserPlugin($parser);

        $index = 16;
        $char = '}';
        $previousChar = '.';
        $state = "T_AT_KEYFRAMES_RULESET_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}
