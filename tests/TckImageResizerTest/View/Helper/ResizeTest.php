<?php
/**
 * Smart image resizing (and manipulation) by url module for Laminas
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2015 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\View\Helper;

use PHPUnit\Framework\TestCase;
use TckImageResizer\View\Helper\Resize;
use Laminas\View\Renderer\PhpRenderer;

class ResizeTest extends TestCase
{
    /** @var  PhpRenderer */
    protected $view;
    /** @var  Resize */
    protected $helper;

    protected function setUp(): void
    {
        $this->view = new PhpRenderer();
        $this->view->getHelperPluginManager()->get('basePath')->setBasePath('/');

        $this->helper = new Resize();
        $this->helper->setView($this->view);
    }
    
    public function testInvoke()
    {
        $resizeObject = $this->helper->__invoke('test.jpg');

        self::assertInstanceOf('TckImageResizer\View\Helper\Resize', $resizeObject);
    }
    
    public function testFileInFolder()
    {
        $actual = $this->helper->__invoke('folder/test.jpg')->__toString();

        self::assertEquals('/processed/folder/test..jpg', $actual);
    }
    
    public function testThumb()
    {
        $actual = $this->helper->__invoke('test.jpg')->thumb(300, 200)->__toString();

        self::assertEquals('/processed/test.$thumb,300,200.jpg', $actual);
    }
    
    public function testResize()
    {
        $actual = $this->helper->__invoke('test.jpg')->resize(300, 200)->__toString();

        self::assertEquals('/processed/test.$resize,300,200.jpg', $actual);
    }
    
    public function testGrayscale()
    {
        $actual = $this->helper->__invoke('test.jpg')->grayscale()->__toString();

        self::assertEquals('/processed/test.$grayscale.jpg', $actual);
    }
    
    public function testNegative()
    {
        $actual = $this->helper->__invoke('test.jpg')->negative()->__toString();

        self::assertEquals('/processed/test.$negative.jpg', $actual);
    }
    
    public function testGamma()
    {
        $actual = $this->helper->__invoke('test.jpg')->gamma(1)->__toString();

        self::assertEquals('/processed/test.$gamma,1.jpg', $actual);
    }
    
    public function testColorize()
    {
        $actual = $this->helper->__invoke('test.jpg')->colorize('ff00ff')->__toString();

        self::assertEquals('/processed/test.$colorize,ff00ff.jpg', $actual);
    }
    
    public function testSharpen()
    {
        $actual = $this->helper->__invoke('test.jpg')->sharpen()->__toString();

        self::assertEquals('/processed/test.$sharpen.jpg', $actual);
    }
    
    public function testBlur()
    {
        $actual = $this->helper->__invoke('test.jpg')->blur(1)->__toString();

        self::assertEquals('/processed/test.$blur,1.jpg', $actual);
    }
    
    public function test404()
    {
        $actual = $this->helper->__invoke('test.jpg')
            ->x404('File not found', 'ffffff', 'ff00ff', 400, 200)
            ->__toString();

        self::assertEquals('/processed/test.$404,RmlsZSBub3QgZm91bmQ,ffffff,ff00ff,400,200.jpg', $actual);
    }
}
