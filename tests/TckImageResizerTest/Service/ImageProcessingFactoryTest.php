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

namespace TckImageResizerTest\Service;

use TckImageResizerTest\Bootstrap;
use TckImageResizer\Service\ImageProcessingFactory;
use PHPUnit\Framework\TestCase;

class ImageProcessingFactoryTest extends TestCase
{
    /** @var  serviceManager */
    protected $serviceManager;
    /** @var  ImageProcessingFactory */
    protected $imageProcessingFactory;

    protected function setUp(): void
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->imageProcessingFactory = new ImageProcessingFactory();
    }
    
    public function testCreateService()
    {
        $imageProcessing = $this->imageProcessingFactory->createService($this->serviceManager);
        
        self::assertInstanceOf('TckImageResizer\Service\ImageProcessing', $imageProcessing);
    }
}
