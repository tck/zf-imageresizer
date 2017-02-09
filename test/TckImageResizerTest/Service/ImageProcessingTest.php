<?php
/**
 * Smart image resizing (and manipulation) by url module for Zend Framework 3
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2017 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\Service;

use TckImageResizerTest\Bootstrap;
use TckImageResizer\Service\ImageProcessing;
use TckImageResizer\Service\CommandRegistry;
use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use TckImageResizer\Exception\BadMethodCallException;

class ImageProcessingTest extends PHPUnit_Framework_TestCase
{
    /** @var  \Zend\ServiceManager\ServiceManager */
    protected $serviceManager;
    protected $fileSystem;
    /** @var  \Imagine\Gd\Imagine */
    protected $imagine;
    /** @var  ImageProcessing */
    protected $imageProcessing;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        
        $this->fileSystem = vfsStream::setup('public', null, array(
            'img' => array(
                'test.jpg' => file_get_contents(__DIR__ . '/../../_files/test.jpg'),
            ),
            'processed' => array(),
        ));
        
        $this->imagine = $this->serviceManager->get('TckImageResizerImagine');
        
        $this->imageProcessing = new ImageProcessing($this->imagine);
    }
    
    public function testGetImagineService()
    {
        $imagine = $this->imageProcessing->getImagineService();
        
        $this->assertInstanceOf('Imagine\Image\AbstractImagine', $imagine);
    }
    
    public function testSetImagineService()
    {
        $imageProcessing = $this->imageProcessing->setImagineService($this->imagine);
        
        $this->assertInstanceOf('TckImageResizer\Service\ImageProcessing', $imageProcessing);
    }
    
    public function testProcessCommandThumb()
    {
        $commands = 'thumb,100,100';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
        
        $imageInfo = getimagesizefromstring($content);
        
        $this->assertEquals(100, $imageInfo[0]);
        
        $this->assertGreaterThan(0, $imageInfo[1]);
        $this->assertLessThan(100, $imageInfo[1]);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage thumb
     */
    public function testExceptionProcessCommandThumbWidth()
    {
        $commands = 'thumb,0,100';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage thumb
     */
    public function testExceptionProcessCommandThumbHeight()
    {
        $commands = 'thumb,100,0';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
    }
    
    public function testProcessCommandResize()
    {
        $commands = 'resize,100,100';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
        
        $imageInfo = getimagesizefromstring($content);
        
        $this->assertEquals(100, $imageInfo[0]);
        $this->assertEquals(100, $imageInfo[1]);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage resize
     */
    public function testExceptionProcessCommandResizeWidth()
    {
        $commands = 'resize,0,100';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage resize
     */
    public function testExceptionProcessCommandResizeHeight()
    {
        $commands = 'resize,100,0';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
    }
    
    public function testProcessCommandGrayscale()
    {
        $commands = 'grayscale';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }
    
    public function testProcessCommandNegative()
    {
        $commands = 'negative';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }
    
    public function testProcessCommandGamma()
    {
        $commands = 'gamma,0.75';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }
    
    public function testProcessCommandColorize()
    {
        $commands = 'colorize,ff0000';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage colorize
     */
    public function testExceptionProcessCommandColorizeColor()
    {
        $commands = 'colorize,nohexcolor';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
    }
    
    public function testProcessCommandSharpen()
    {
        $commands = 'sharpen';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }
    
    public function testProcessCommandBlur()
    {
        $commands = 'blur';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }
    
    public function testProcessCustomCommand()
    {
        $commands = 'custom';
        
        $callCheck = false;
        CommandRegistry::register('custom', function () use (&$callCheck) {
            $callCheck = true;
        });
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
        
        $this->assertTrue($callCheck);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('test.$' . $commands . '.jpg'));
        
        $content = $folder->getChild('test.$' . $commands . '.jpg')->getContent();
        
        $this->assertEquals(pack('H*', 'ffd8ff'), substr($content, 0, 3)); // ÿØÿ
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage nonexistentcommand
     */
    public function testExceptionProcessCustomCommand()
    {
        $commands = 'nonexistentcommand';
        
        $source = vfsStream::url('public') . '/img/test.jpg';
        $target = vfsStream::url('public') . '/processed/img/test.$' . $commands . '.jpg';
        
        $this->imageProcessing->process($source, $target, $commands);
    }
    
    public function testProcess404()
    {
        $commands = 'resize,300,100$404,NotFound,000000,ffffff,300,100';
        
        $target = vfsStream::url('public') . '/processed/img/nonexistentimage.$' . $commands . '.jpg.404.png';
        
        $this->imageProcessing->process404($target, $commands);
        
        $folder = vfsStreamWrapper::getRoot()->getChild('processed')->getChild('img');
        
        $this->assertTrue($folder->hasChild('nonexistentimage.$' . $commands . '.jpg.404.png'));
        
        $mTimeBefore = $folder->getChild('nonexistentimage.$' . $commands . '.jpg.404.png')->filemtime();
        
        sleep(2);
        
        $this->imageProcessing->process404($target, $commands);
        
        $mTimeAfter = $folder->getChild('nonexistentimage.$' . $commands . '.jpg.404.png')->filemtime();
        
        $this->assertTrue($mTimeBefore === $mTimeAfter);
        
        
        $noSizeCommands = 'blur';
        
        $noSizeTarget = vfsStream::url('public')
            . '/processed/img/nonexistentimage.$' . $noSizeCommands . '.jpg.404.png';
        
        $this->imageProcessing->process404($noSizeTarget, $noSizeCommands);
        
        $this->assertTrue($folder->hasChild('nonexistentimage.$' . $noSizeCommands . '.jpg.404.png'));
    }
}
