<?php
/**
 * Smart image resizing (and manipulation) by url module for Zend Framework 2
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2015 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\View\Helper;

use PHPUnit_Framework_TestCase;
use TckImageResizer\View\Helper\Resize;
use Zend\View\Renderer\PhpRenderer;

class ResizeTest extends PHPUnit_Framework_TestCase
{
    /** @var  PhpRenderer */
    protected $view;
    /** @var  Resize */
    protected $helper;

    protected function setUp()
    {
        $this->view = new PhpRenderer();
        $this->view->getHelperPluginManager()->get('basePath')->setBasePath('/');

        $this->helper = new Resize();
        $this->helper->setView($this->view);
    }
    
    public function testInvoke()
    {
        $resizeObject = $this->helper->__invoke('test.jpg');
        
        $this->assertInstanceOf('TckImageResizer\View\Helper\Resize', $resizeObject);
    }
    
    public function testFileInFolder()
    {
        $actual = $this->helper->__invoke('folder/test.jpg')->__toString();
        
        $this->assertEquals('/processed/folder/test..jpg', $actual);
    }
    
    public function testThumb()
    {
        $actual = $this->helper->__invoke('test.jpg')->thumb(300, 200)->__toString();
        
        $this->assertEquals('/processed/test.$thumb,300,200.jpg', $actual);
    }
    
    public function testResize()
    {
        $actual = $this->helper->__invoke('test.jpg')->resize(300, 200)->__toString();
        
        $this->assertEquals('/processed/test.$resize,300,200.jpg', $actual);
    }
    
    public function testGrayscale()
    {
        $actual = $this->helper->__invoke('test.jpg')->grayscale()->__toString();
        
        $this->assertEquals('/processed/test.$grayscale.jpg', $actual);
    }
    
    public function testNegative()
    {
        $actual = $this->helper->__invoke('test.jpg')->negative()->__toString();
        
        $this->assertEquals('/processed/test.$negative.jpg', $actual);
    }
    
    public function testGamma()
    {
        $actual = $this->helper->__invoke('test.jpg')->gamma(1)->__toString();
        
        $this->assertEquals('/processed/test.$gamma,1.jpg', $actual);
    }
    
    public function testColorize()
    {
        $actual = $this->helper->__invoke('test.jpg')->colorize('ff00ff')->__toString();
        
        $this->assertEquals('/processed/test.$colorize,ff00ff.jpg', $actual);
    }
    
    public function testSharpen()
    {
        $actual = $this->helper->__invoke('test.jpg')->sharpen()->__toString();
        
        $this->assertEquals('/processed/test.$sharpen.jpg', $actual);
    }
    
    public function testBlur()
    {
        $actual = $this->helper->__invoke('test.jpg')->blur(1)->__toString();
        
        $this->assertEquals('/processed/test.$blur,1.jpg', $actual);
    }
    
    public function test404()
    {
        $actual = $this->helper->__invoke('test.jpg')
            ->x404('File not found', 'ffffff', 'ff00ff', 400, 200)
            ->__toString();
        
        $this->assertEquals('/processed/test.$404,RmlsZSBub3QgZm91bmQ,ffffff,ff00ff,400,200.jpg', $actual);
    }
}
