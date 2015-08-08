<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Joli\Php7Checker\tests\Functional;

class OpeningAndClosingTagsTest extends AbstractFunctionalTestCase
{
    public function testItFindsNoErrorWithPhpTags()
    {
        $this->assertErrors([], 'Checker/OpeningAndClosingTags/PhpTags.php');
    }

    public function testItFindsNoErrorWithPhpShortTags()
    {
        $this->assertErrors([], 'Checker/OpeningAndClosingTags/PhpShortTags.php');
    }

    public function testItFindsNoErrorWithPhpEchoTags()
    {
        $this->assertErrors([], 'Checker/OpeningAndClosingTags/PhpEchoTags.php');
    }

    public function testItFindsErrorWithAspTags()
    {
        $this->assertErrors([
            [1, '"<%" can no longer be used as PHP opening tag'],
            [3, '"%>" can no longer be used as PHP closing tag'],
        ], 'Checker/OpeningAndClosingTags/AspTags.php');
    }

    public function testItFindsErrorWithAspEchoTags()
    {
        $this->assertErrors([
            [1, '"<%=" can no longer be used as PHP opening tag'],
            [3, '"%>" can no longer be used as PHP closing tag'],
        ], 'Checker/OpeningAndClosingTags/AspEchoTags.php');
    }

    public function testItFindsErrorWithScriptTags()
    {
        $this->assertErrors([
            [1, '"<script language=php>" can no longer be used as PHP opening tag'],
            [3, '"</script>" can no longer be used as PHP closing tag'],
        ], 'Checker/OpeningAndClosingTags/ScriptTags.php');
    }

    public function testItFindsErrorWithMixedTags()
    {
        $this->assertErrors([
            [1, '"<script language=php>" can no longer be used as PHP opening tag'],
            [3, '"%>" can no longer be used as PHP closing tag'],
        ], 'Checker/OpeningAndClosingTags/MixedTags.php');
    }
}
