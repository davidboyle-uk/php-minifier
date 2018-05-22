<?php

namespace PHPMin\HtmlMin\Test;

use PHPUnit\Framework\TestCase;
use PHPMin\HtmlMin;

/**
 * Tests for HtmlMin
 *
 * @package HtmlMin/Test
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class HtmlMinTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../data/HtmlMin';

    /* HtmlMin */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new HtmlMin();
    }

    public function testBasicHtmlMinification()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.html';
        $outputFile = $this->dataDir . '/basic-minification-out.html';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut::Minify($testInput);

        $this->assertSame($expected, $actual);
    }
}
