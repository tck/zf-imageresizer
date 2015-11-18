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

return array(
    'router' => array(
        'routes' => array(
            'tckimageresizer' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/processed',
                    'defaults' => array(
                        '__NAMESPACE__' => 'TckImageResizer\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'resize' => array(
                        'type' => 'Zend\Mvc\Router\Http\Regex',
                        'options' => array(
                            'regex'    => '/(?<file>.*?)\.\$(?<command>.*)\.(?<extension>[a-zA-Z]+)',
                            'defaults' => array(
                                'action'     => 'resize',
                            ),
                            'spec' => '/processed/%file%.$%command%.%extension%',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'TckImageResizerImagine' => 'Imagine\Gd\Imagine',
        ),
        'factories' => array(
            'TckImageResizer\Service\ImageProcessing' => 'TckImageResizer\Service\ImageProcessingFactory',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'TckImageResizer\Controller\Index' => 'TckImageResizer\Controller\IndexControllerFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'resize' => 'TckImageResizer\View\Helper\Resize'
        ),
    ),
);
