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
 
namespace TckImageResizer\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Index controller factory
 * 
 * @package TckImageResizer
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     *
     * @param \Zend\Mvc\Controller\ControllerManager|ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();
        $imageProcessing = $sm->get('TckImageResizer\Service\ImageProcessing');
        $controller = new IndexController($imageProcessing);
        return $controller;
    }
}
