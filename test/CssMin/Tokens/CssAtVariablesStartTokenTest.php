<?php

namespace PHPMin\CssMin\Test\Tokens;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin\Tokens\CssAtVariablesStartToken;

/**
 * Tests for CssAtVariablesStartToken
 *
 * @package CssMin/Test/Tokens
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtVariablesStartTokenTest extends TestCase
{
    public function testCssAtVariablesStartToken()
    {
        $token = new CssAtVariablesStartToken();
        $this->assertSame($token->__toString(), "");
    }
}
