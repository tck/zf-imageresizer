<?php
/**
 * Smart image resizing (and manipulation) by url module for Laminas
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2017 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\Util;

use TckImageResizer\Util\UrlSafeBase64;
use PHPUnit\Framework\TestCase;

class UrlSafeBase64Test extends TestCase
{
    public function testEncode()
    {
        $actual = UrlSafeBase64::encode('test');

        self::assertEquals('dGVzdA', $actual);
    }

    public function testDecode()
    {
        $actual = UrlSafeBase64::decode('dGVzdA');

        self::assertEquals('test', $actual);
    }
    
    public function testVaild()
    {
        $validTrue = UrlSafeBase64::valid('dGVzdA');

        self::assertTrue($validTrue);
        
        $validFalse = UrlSafeBase64::valid('test!');

        self::assertFalse($validFalse);
    }
}
