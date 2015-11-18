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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use TckImageResizer\Service\ImageProcessing;

/**
 * Index controller
 * 
 * @package TckImageResizer
 */
class IndexController extends AbstractActionController
{
    /**
     * @var ImageProcessing
     */
    protected $imageProcessing;
    
    /**
     * @var string
     */
    protected $publicDirectory;
    
    /**
     * constructor
     *
     * @param ImageProcessing $imageProcessing
     * @param string $publicDirectory
     */
    public function __construct(ImageProcessing $imageProcessing, $publicDirectory = 'public')
    {
        $this->setImageProcessing($imageProcessing);
        $this->publicDirectory = $publicDirectory;
    }
    
    /**
     * set the image processing service
     *
     * @param ImageProcessing $imageProcessing
     * @return $this
     */
    public function setImageProcessing(ImageProcessing $imageProcessing)
    {
        $this->imageProcessing = $imageProcessing;

        return $this;
    }
    
    /**
     * Get the image processing service
     * 
     * @return ImageProcessing
     */
    public function getImageProcessing()
    {
        return $this->imageProcessing;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function resizeAction()
    {
        $source = $this->publicDirectory . '/'
                . $this->params('file')
                . '.' . $this->params('extension');
        
        $targetExtension = $this->params('extension');
        if (!file_exists($source)) {
            $source = null;
            $targetExtension = '404.' . $targetExtension . '.png';
        }
        
        $target = $this->publicDirectory . '/processed/'
                . $this->params('file')
                . '.$' . $this->params('command')
                . '.' . $targetExtension;
        
        if ($source) {
            $this->getImageProcessing()->process($source, $target, $this->params('command'));
            
        } else {
            $this->getImageProcessing()->process404($target, $this->params('command'));
        }
        
        $imageInfo = getimagesize($target);
        $mimeType = $imageInfo['mime'];

        /** @var \Zend\Http\Response $response */
        $response = $this->getResponse();
        $response->setContent(file_get_contents($target));
        $response->setStatusCode($source ? 200 : 404);
        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', $mimeType);
        
        return $response;
    }
}
