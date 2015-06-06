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

namespace TckImageResizerTest\Controller;

use TckImageResizerTest\Bootstrap;
use TckImageResizer\Controller\IndexControllerFactory;
use PHPUnit_Framework_TestCase;

class IndexControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $controllerManager;
    protected $controllerFactory;

    protected function setUp()
    {
        $this->controllerManager = Bootstrap::getServiceManager()->get('ControllerLoader');
        $this->controllerFactory = new IndexControllerFactory();
    }
    
    public function testCreateService()
    {
        $controller = $this->controllerFactory->createService($this->controllerManager);
        
        $this->assertInstanceOf('TckImageResizer\Controller\IndexController', $controller);
    }
}
