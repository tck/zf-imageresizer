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

namespace TckImageResizer;

use Imagine\Gd\Imagine;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Regex;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'tckimageresizer' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/processed',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'resize' => [
                        'type' => Regex::class,
                        'options' => [
                            'regex'    => '/(?<file>.*?)\.\$(?<command>.*)\.(?<extension>[a-zA-Z]+)',
                            'defaults' => [
                                'action'     => 'resize',
                            ],
                            'spec' => '/processed/%file%.$%command%.%extension%',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'TckImageResizerImagine' => Imagine::class,
        ],
        'factories' => [
            Imagine::class => InvokableFactory::class,
            Service\ImageProcessing::class => Service\ImageProcessingFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\IndexControllerFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'resize' => View\Helper\Resize::class,
        ],
        'factories' => [
            View\Helper\Resize::class => InvokableFactory::class,
        ],
    ],
];
