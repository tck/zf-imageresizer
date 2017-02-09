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
use TckImageResizer\Service\ImageProcessingFactory;
use PHPUnit_Framework_TestCase;

class ImageProcessingFactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var  serviceManager */
    protected $serviceManager;
    /** @var  ImageProcessingFactory */
    protected $imageProcessingFactory;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->imageProcessingFactory = new ImageProcessingFactory();
    }
    
    public function testCreateService()
    {
        $imageProcessing = $this->imageProcessingFactory->createService($this->serviceManager);
        
        $this->assertInstanceOf('TckImageResizer\Service\ImageProcessing', $imageProcessing);
    }
}
