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

namespace TckImageResizerTest\Controller;

use TckImageResizerTest\Bootstrap;
use Laminas\Router\Http\TreeRouteStack as HttpRouter;
use TckImageResizer\Controller\IndexController;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class IndexControllerTest extends TestCase
{
    /** @var  IndexController */
    protected $controller;
    /** @var  Request */
    protected $request;
    /** @var  RouteMatch */
    protected $routeMatch;
    /** @var  MvcEvent */
    protected $event;
    /** @var  \org\bovigo\vfs\vfsStreamDirectory */
    protected $fileSystem;

    protected function setUp(): void
    {
        $serviceManager = Bootstrap::getServiceManager();
        
        $this->fileSystem = vfsStream::setup('public', null, [
            'img' => [
                'test.jpg' => file_get_contents(__DIR__ . '/../../_files/test.jpg'),
            ],
            'processed' => [],
        ]);

        /** @var \TckImageResizer\Service\ImageProcessing $imageProcessing */
        $imageProcessing = $serviceManager->get('TckImageResizer\Service\ImageProcessing');
        $this->controller = new IndexController($imageProcessing, vfsStream::url('public'));
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(['controller' => 'index']);
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : [];
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }
    
    public function testResizeActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'resize');
        $this->routeMatch->setParam('file', 'img/test');
        $this->routeMatch->setParam('extension', 'jpg');
        $this->routeMatch->setParam('command', 'thumb,80,40');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        self::assertEquals(200, $response->getStatusCode());
    }
    
    public function testResizeActionContentTypeImageJpeg()
    {
        $this->routeMatch->setParam('action', 'resize');
        $this->routeMatch->setParam('file', 'img/test');
        $this->routeMatch->setParam('extension', 'jpg');
        $this->routeMatch->setParam('command', 'thumb,80,40');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        self::assertStringStartsWith('image/jpeg', $response->getHeaders()->get('Content-Type')->getFieldValue());
    }
    
    public function testResizeActionResponseFileSignatureJpeg()
    {
        $this->routeMatch->setParam('action', 'resize');
        $this->routeMatch->setParam('file', 'img/test');
        $this->routeMatch->setParam('extension', 'jpg');
        $this->routeMatch->setParam('command', 'thumb,80,40');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        self::assertEquals(pack('H*', 'ffd8ff'), substr($response->getBody(), 0, 3)); // ÿØÿ
    }
    
    public function testResizeActionFileNotFound()
    {
        $this->routeMatch->setParam('action', 'resize');
        $this->routeMatch->setParam('file', 'img/test/fileNotFound');
        $this->routeMatch->setParam('extension', 'jpg');
        $this->routeMatch->setParam('command', 'thumb,80,40');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        self::assertEquals(404, $response->getStatusCode());
    }
}
