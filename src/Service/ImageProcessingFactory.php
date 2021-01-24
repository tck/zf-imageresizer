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

/**
 * namespace definition and usage
 */
namespace TckImageResizer\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Create image processing service
 *
 * @package Application
 */
class ImageProcessingFactory implements FactoryInterface
{
    /**
     * Create an ImageProcessing object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return ImageProcessing
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ImageProcessing($container->get('TckImageResizerImagine'));
    }

    public function createService(ServiceLocatorInterface $services)
    {
        return $this($services, 'TckImageResizerImagine');
    }
}
