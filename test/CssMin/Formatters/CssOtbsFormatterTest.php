<?php

namespace PHPMin\Test\CssMin\Formatters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Formatters\CssOtbsFormatter;
use PHPMin\CssMin\Tokens\CssCommentToken;
use PHPMin\CssMin\Tokens\CssAtCharsetToken;
use PHPMin\CssMin\Tokens\CssAtFontFaceStartToken;
use PHPMin\CssMin\Tokens\CssAtImportToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesStartToken;
use PHPMin\CssMin\Tokens\CssAtMediaStartToken;
use PHPMin\CssMin\Tokens\CssAtPageStartToken;
use PHPMin\CssMin\Tokens\CssAtVariablesStartToken;
use PHPMin\CssMin\Tokens\CssRulesetStartToken;
use PHPMin\CssMin\Tokens\CssAtFontFaceDeclarationToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetStartToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetDeclarationToken;
use PHPMin\CssMin\Tokens\CssAtPageDeclarationToken;
use PHPMin\CssMin\Tokens\CssAtVariablesDeclarationToken;
use PHPMin\CssMin\Tokens\CssRulesetDeclarationToken;
use PHPMin\CssMin\Tokens\CssAtFontFaceEndToken;
use PHPMin\CssMin\Tokens\CssAtMediaEndToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesEndToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetEndToken;
use PHPMin\CssMin\Tokens\CssAtPageEndToken;
use PHPMin\CssMin\Tokens\CssAtVariablesEndToken;
use PHPMin\CssMin\Tokens\CssRulesetEndToken;

/**
 * Tests for CssOtbsFormatter
 *
 * @package CssMin/Test/Formatters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssOtbsFormatterTest extends TestCase
{
    /* CssOtbsFormatter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => false,
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => false,
        "RemoveEmptyAtBlocks"           => false,
        "ConvertLevel3Properties"       => false,
        "ConvertLevel3AtKeyframes"      => false,
        "Variables"                     => false,
        "RemoveLastDelarationSemiColon" => false
    ];

    /* Plugin Configuration */
    protected $plugins = [
        "Variables"                     => false,
        "ConvertFontWeight"             => false,
        "ConvertHslColors"              => false,
        "ConvertRgbColors"              => false,
        "ConvertNamedColors"            => false,
        "CompressColorValues"           => false,
        "CompressUnitValues"            => false,
        "CompressExpressionValues"      => false
    ];

    /* CssMin */
    protected $minifier;

    protected function setUp()
    {
        $this->minifier = new CssMin(
            null,
            $this->filters,
            $this->plugins
        );
    }

    public function testCssOtbsFormatterCssComment()
    {
        $tokens = [
            new CssCommentToken('/* This is a comment and will not need to remain when minified */'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '/* This is a comment and will not need to remain when minified */';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtCharset()
    {
        $tokens = [
            new CssAtCharsetToken('UTF-8'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@charset UTF-8;';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtFontFace()
    {
        $tokens = [
            new CssAtFontFaceStartToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@font-face {';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtImport()
    {
        $tokens = [
            new CssAtImportToken('nav.css', ['screen']),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@import nav.css screen;';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtKeyframesStart()
    {
        $tokens = [
            new CssAtKeyframesStartToken('myMove'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@keyframes "myMove" {';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtMediaStart()
    {
        $tokens = [
            new CssAtMediaStartToken(['print', 'screen']),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@media print, screen {';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtPageStart()
    {
        $tokens = [
            new CssAtPageStartToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@page {';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssAtVariablesStart()
    {
        $tokens = [
            new CssAtVariablesStartToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '@variables all {';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssOtbsFormatterCssRulesetStart()
    {
        $tokens = [
            new CssRulesetStartToken(['.className']),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '.className {';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtFontFaceDeclaration()
    {
        $tokens = [
            new CssAtFontFaceDeclarationToken('font-family', 'myFirstFont'),
        ];

        $this->sut = new CssOtbsFormatter($tokens, 4, 10);

        $expected = 'font-family: myFirstFont;';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtKeyframesRulesetDeclaration()
    {
        $tokens = [
            new CssAtKeyframesRulesetDeclarationToken('from', '{top: 0px}'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = 'from: {top: 0px};';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtPageDeclaration()
    {
        $tokens = [
            new CssAtPageDeclarationToken('margin', '1cm'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = 'margin: 1cm;';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtVariablesDeclaration()
    {
        $tokens = [
            new CssAtVariablesDeclarationToken('--main-bg-color', 'brown'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = '--main-bg-color: brown;';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssRulesetDeclaration()
    {
        $tokens = [
            new CssRulesetDeclarationToken('padding', '10px'),
        ];

        $this->sut = new CssOtbsFormatter($tokens);

        $expected = 'padding: 10px;';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtFontFaceEnd()
    {
        $tokens = [
            new CssAtFontFaceStartToken(),
            new CssAtFontFaceEndToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens, '', 10);

        $expected = '@font-face {' . "\n" . '}';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtMediaEnd()
    {
        $tokens = [
            new CssAtMediaStartToken(['print', 'screen']),
            new CssAtMediaEndToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens, '', 10);

        $expected = '@media print, screen {' . "\n" . '}';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtKeyframesEnd()
    {
        $tokens = [
            new CssAtKeyframesStartToken('myRule'),
            new CssAtKeyframesEndToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens, '', 10);

        $expected = '@keyframes "myRule" {' . "\n" . '}';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtKeyframesRulesetEnd()
    {
        $tokens = [
            new CssAtKeyframesRulesetStartToken(['.className']),
            new CssAtKeyframesRulesetEndToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens, '', 10);

        $expected = '.className {' . "\n" . '}';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssAtPageEnd()
    {
        $tokens = [
            new CssAtPageStartToken(),
            new CssAtPageEndToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens, '', 10);

        $expected = '@page {' . "\n" . '}';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCCssRulesetEnd()
    {
        $tokens = [
            new CssRulesetStartToken(['.className']),
            new CssRulesetEndToken(),
        ];

        $this->sut = new CssOtbsFormatter($tokens, '', 10);

        $expected = '.className {' . "\n" . '}';
        
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }
}
