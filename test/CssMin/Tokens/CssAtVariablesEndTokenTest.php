<?php

namespace PHPMin\CssMin\Test\Tokens;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin\Tokens\CssAtVariablesEndToken;

/**
 * Tests for CssAtVariablesEndToken
 *
 * @package CssMin/Test/Tokens
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtVariablesEndTokenTest extends TestCase
{
    public function testCssAtVariablesEndToken()
    {
        $token = new CssAtVariablesEndToken();
        $this->assertSame($token->__toString(), "");
    }
}
