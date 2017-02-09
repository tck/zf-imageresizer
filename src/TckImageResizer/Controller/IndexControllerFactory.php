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
 
namespace TckImageResizer\Controller;

use Interop\Container\ContainerInterface;
use TckImageResizer\Service\ImageProcessing;
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
     * Create an IndexController object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController($container->get(ImageProcessing::class));
    }

    public function createService(ServiceLocatorInterface $services)
    {
        return $this($services, IndexController::class);
    }
}
