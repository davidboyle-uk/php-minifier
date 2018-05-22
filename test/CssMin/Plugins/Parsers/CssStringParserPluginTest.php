<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssStringParserPlugin;

/**
 * Tests for CssStringParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssStringParserPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Parsers';

    /* Plugin Configuration */
    protected $plugins = [
        "Comment"                       => false,
        "String"                        => true,
        "Url"                           => false,
        "Expression"                    => false,
        "Ruleset"                       => false,
        "AtCharset"                     => false,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];
    
    public function testCssStringParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssStringParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssStringParserPlugin-parse-out.ser';

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

    public function testCssStringParserPluginNewlineParse()
    {
        $expected = true;

        $parser = new CssParser(
            null,
            $this->plugins
        );
        $plugin = new CssStringParserPlugin($parser);

        $index = 17;
        $char = "\n";
        $previousChar = ' ';
        $state = "T_STRING";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssStringParserPluginDelimiterParse()
    {
        $expected = true;

        $parser = new CssParser(
            '.test {\\}',
            $this->plugins
        );
        $plugin = new CssStringParserPlugin($parser);

        $index = 9;
        $char = "'";
        $previousChar = "\\";

        $state = "";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_STRING";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssStringParserPluginUnevenDelimiterParse()
    {
        $expected = false;

        $parser = new CssParser(
            '.test {\\\}',
            $this->plugins
        );
        $plugin = new CssStringParserPlugin($parser);

        $index = 11;
        $char = "'";
        $previousChar = "\\";

        $state = "";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_STRING";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssStringParserPluginFalseParse()
    {
        $expected = false;

        $parser = new CssParser(
            null,
            $this->plugins
        );
        $plugin = new CssStringParserPlugin($parser);

        $index = 17;
        $char = "'";
        $previousChar = ' ';
        $state = "T_STRING";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}
