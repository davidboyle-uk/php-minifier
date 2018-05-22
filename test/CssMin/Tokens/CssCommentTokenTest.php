<?php

namespace PHPMin\CssMin\Test\Tokens;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin\Tokens\CssCommentToken;

/**
 * Tests for CssCommentToken
 *
 * @package CssMin/Test/Tokens
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssCommentTokenTest extends TestCase
{
    public function testCssCommentToken()
    {
        $token = new CssCommentToken('/* This is a comment and will not need to remain when minified */');
        $this->assertSame($token->__toString(), "/* This is a comment and will not need to remain when minified */");
    }
}
