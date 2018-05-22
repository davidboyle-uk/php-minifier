<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssUrlParserPlugin;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssUrlParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssUrlParserPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Parsers';

    /* Plugin Configuration */
    protected $plugins = [
        "Comment"                       => false,
        "String"                        => false,
        "Url"                           => true,
        "Expression"                    => false,
        "Ruleset"                       => true,
        "AtCharset"                     => false,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];
    
    public function testCssUrlParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssUrlParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssUrlParserPlugin-parse-out.ser';

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

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssUrlParserPluginNewlineParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssUrlParserPlugin($parser);

        $index = 17;
        $char = "\n";
        $previousChar = ' ';
        $state = "T_URL";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssUrlParserPluginPreviousSlashParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssUrlParserPlugin($parser);

        $index = 17;
        $char = "\n";
        $previousChar = "\\";
        $state = "T_URL";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssUrlParserPluginPreviousFalseParse()
    {
        $expected = false;

        $parser = new CssParser();
        $plugin = new CssUrlParserPlugin($parser);

        $index = 17;
        $char = "\n";
        $previousChar = "\\";
        $state = "";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}
