<?php

namespace PHPMin\Test;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Tokens\CssNullToken;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssParser
 *
 * @package CssMin/Test
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssParserTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../data/CssMin';

    public function testCssParserBuffer()
    {
        $sut = new CssParser();
        $this->assertSame("", $sut->getBuffer());

        $bufferIn = '.test{}';
        $sut->setBuffer($bufferIn);

        $this->assertSame($bufferIn, $sut->getBuffer());

        $sut->clearBuffer();
        $this->assertSame("", $sut->getBuffer());
    }

    public function testCssParserTokens()
    {
        $sut = new CssParser();

        $this->assertSame(array(), $sut->getTokens());

        $testToken = new CssNullToken();

        $sut->appendToken($testToken);
        $this->assertSame(array($testToken), $sut->getTokens());
    }

    public function testCssParserMediaTypes()
    {
        $sut = new CssParser();
        $this->assertSame(false, $sut->getMediaTypes());

        $mediaTypes = array('all');
        $sut->setMediaTypes($mediaTypes);

        $this->assertSame($mediaTypes, $sut->getMediaTypes());

        $sut->unsetMediaTypes();
        $this->assertSame(false, $sut->getMediaTypes());
    }

    public function testCssParserSource()
    {
        $sut = new CssParser();
        $this->assertSame("", $sut->getSource());

        $sourceIn = '.test{}';
        $sut = new CssParser($sourceIn);

        $this->assertSame($sourceIn, $sut->getSource());
    }

    public function testCssParserStates()
    {
        $sut = new CssParser();
        $this->assertSame(array("T_DOCUMENT"), $sut->getStates());

        $this->assertSame("T_DOCUMENT", $sut->getState());

        $this->assertSame(true, $sut->isState("T_DOCUMENT"));

        $sut->pushState("T_AT_MEDIA::PREPARE");
        $this->assertSame("T_AT_MEDIA::PREPARE", $sut->getState());

        $sut->popState();
        $this->assertSame(array("T_DOCUMENT"), $sut->getStates());
        $this->assertSame("T_DOCUMENT", $sut->getState());

        $sut->setState("T_AT_MEDIA::PREPARE");
        $this->assertSame("T_AT_MEDIA::PREPARE", $sut->getState());
    }

    public function testCssParserExclusiveState()
    {
        $inputFile = $this->dataDir . '/CssParserExlusivePlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssParserExlusivePlugin-parse-out.ser';

        $testInput = file_get_contents($inputFile);
        $expected = unserialize(file_get_contents($outputFile));

        $sut = new CssParser(
            $testInput,
            [
            "Comment" => true,
            "String" => true,
                "AtCharset" => true
            ]
        );

        $sut->setExclusive("PHPMin\CssMin\Plugins\Parsers\CssAtCharsetParserPlugin");

        $actual = $sut->parse($testInput);

        $this->assertSame(serialize($expected), serialize($actual));
    }

    public function testCssParserGetKnownPlugin()
    {
        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(false);

        $sut = new CssParser(
            null,
            [
                "Comment" => true
            ]
        );

        $plugin = $sut->getPlugin("PHPMin\CssMin\Plugins\Parsers\CssCommentParserPlugin");
        $this->assertSame(false, empty($plugin));
    }

    public function testCssParserGetUnknownPlugin()
    {
        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(false);

        $sut = new CssParser(
            null,
            [
                "Unknown" => true
            ]
        );

        $plugin = $sut->getPlugin("PHPMin\CssMin\Plugins\Parsers\CssCommentParserPlugin");
        $this->assertSame(false, empty($plugin));
    }

    public function testCssParserBadPluginNameError()
    {
        $expectedNumErrors = 1;

        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(false);

        $sut = new CssParser(
            null,
            [
                "NonExistentPluginName" => true
            ]
        );

        $this->assertSame($expectedNumErrors, sizeof($minifier->getErrors()));
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssParserBadPluginNameException()
    {
        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(true);

        $sut = new CssParser(
            null,
            [
                "NonExistentPluginName" => true
            ]
        );
    }
}
