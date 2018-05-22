<?php

namespace PHPMin\JsMin\Test;

use PHPUnit\Framework\TestCase;
use PHPMin\JsMin;

/**
 * Tests for JsMin
 *
 * @package JsMin/Test
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class JsMinTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../data/JsMin';

    /* JsMin */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new JsMin();
    }

    public function testBasicJsMinification()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.js';
        $outputFile = $this->dataDir . '/basic-minification-out.js';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut::Minify($testInput);

        $this->assertSame($expected, $actual);
    }

    public function testBasicJsDoesntDoubleUpMinification()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.js';
        $outputFile = $this->dataDir . '/basic-minification-out.js';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);

        $this->assertSame($this->sut->min($testInput), $actual);
    }

    public function testBasicJsAdditionSubtractionOperatorMinification()
    {
        $inputFile = $this->dataDir . '/addition-subtraction-in.js';
        $outputFile = $this->dataDir . '/addition-subtraction-out.js';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\JsMin\Exceptions\JsMinUnterminatedStringException
     */
    public function testUnterminatedStringException()
    {
        $inputFile = $this->dataDir . '/unterminated-string-in.js';

        $testInput = file_get_contents($inputFile);
        
        $actual = $this->sut->min($testInput);
    }

    /**
     * @expectedException PHPMin\JsMin\Exceptions\JSMinUnterminatedRegExpException
     */
    public function testUnterminatedRegExpSetException()
    {
        $inputFile = $this->dataDir . '/unterminated-regexp-set-in.js';

        $testInput = file_get_contents($inputFile);
        
        $actual = $this->sut->min($testInput);
    }

    /**
     * @expectedException PHPMin\JsMin\Exceptions\JSMinUnterminatedRegExpException
     */
    public function testUnterminatedRegExpException()
    {
        $inputFile = $this->dataDir . '/unterminated-regexp-in.js';
        
        $testInput = file_get_contents($inputFile);
        
        $actual = $this->sut->min($testInput);
    }

    public function testRegExpLiteral()
    {
        $inputFile = $this->dataDir . '/regex-literal-in.js';
        $outputFile = $this->dataDir . '/regex-literal-out.js';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\JsMin\Exceptions\JSMinUnterminatedRegExpException
     */
    public function testLoneKeyword()
    {
        $inputFile = $this->dataDir . '/lone-keyword-in.js';
        $outputFile = $this->dataDir . '/lone-keyword-out.js';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }

    public function testLookaheadControlCharacter()
    {
        $inputFile = $this->dataDir . '/lookahead-control-char-in.js';
        $outputFile = $this->dataDir . '/lookahead-control-char-out.js';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }

    public function testSingleLineConditionalComment()
    {
        $inputFile = $this->dataDir . '/single-line-conditional-comment-in.js';
        
        $testInput = file_get_contents($inputFile);

        $expected = '';
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }

    public function testMultiLineConditionalComment()
    {
        $inputFile = $this->dataDir . '/multi-line-conditional-comment-in.js';
        
        $testInput = file_get_contents($inputFile);

        $expected = '';
        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\JsMin\Exceptions\JSMinUnterminatedCommentException
     */
    public function testUnterminatedConditionalComment()
    {
        $inputFile = $this->dataDir . '/unterminated-conditional-comment-in.js';
        
        $testInput = file_get_contents($inputFile);

        $actual = $this->sut->min($testInput);

        $this->assertSame($expected, $actual);
    }
}
