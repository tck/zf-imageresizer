<?php
/**
 * Smart image resizing (and manipulation) by url module for Zend Framework 2
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2014 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\Util;

use TckImageResizer\Util\UrlSafeBase64;
use PHPUnit_Framework_TestCase;

class UrlSafeBase64Test extends PHPUnit_Framework_TestCase
{
    public function testEncode()
    {
        $actual = UrlSafeBase64::encode('test');
        
        $this->assertEquals('dGVzdA', $actual);
    }

    public function testDecode()
    {
        $actual = UrlSafeBase64::decode('dGVzdA');
        
        $this->assertEquals('test', $actual);
    }
    
    public function testVaild()
    {
        $validTrue = UrlSafeBase64::valid('dGVzdA');
        
        $this->assertTrue($validTrue);
        
        $validFalse = UrlSafeBase64::valid('test!');
        
        $this->assertFalse($validFalse);
    }
}
