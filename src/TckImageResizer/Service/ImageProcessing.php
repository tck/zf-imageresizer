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

namespace TckImageResizer\Service;

use Imagine\Image\AbstractImagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use TckImageResizer\Exception\BadMethodCallException;

/**
 * Image Processing
 * 
 * @package TckImageResizer
 */
class ImageProcessing
{
    /**
     * @var AbstractImagine
     */
    protected $imagine;
    
    /**
     * @var ImageInterface
     */
    protected $image;
    
    
    /**
     * constructor
     * 
     * @param AbstractImagine
     */
    public function __construct(AbstractImagine $imagine)
    {
        $this->setImagineService($imagine);
    }
    
    /**
     * set the imagine service
     * 
     * @param AbstractImagine
     */
    public function setImagineService(AbstractImagine $imagine)
    {
        $this->imagine = $imagine;

        return $this;
    }
    
    /**
     * Get the imagine service
     * 
     * @return AbstractImagine
     */
    public function getImagineService()
    {
        return $this->imagine;
    }
    
    /**
     * Process command to image source and save to target
     * 
     * @param string $source
     * @param string $target
     * @param string $commands
     * 
     * @return void
     */
    public function process($source, $target, $commands)
    {
        $targetFolder = pathinfo($target, PATHINFO_DIRNAME);
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        $this->image = $this->getImagineService()->open($source);
        foreach ($this->analyseCommands($commands) as $command) {
            if ($this->runCommand($command)) {
                continue;
            }
            $this->runCustomCommand($command);
        }
        
        $this->image->save($target);
    }
    
    /**
     * Analyse commands string and returns array with command/params keys
     * 
     * @param string $commands
     * 
     * @return array
     */
    protected function analyseCommands($commands)
    {
        $commandList = array();
        foreach (explode('$', $commands) as $commandLine) {
            $params = explode(',', $commandLine);
            $command = array_shift($params);
            $commandList[] = array(
                'command' => $command,
                'params' => $params,
            );
        }
        return $commandList;
    }
    
    /**
     * Run command if exists
     * 
     * @param array $command
     * 
     * @return boolean
     */
    protected function runCommand($command)
    {
        $method = 'image' . ucfirst(strtolower($command['command']));
        if (!method_exists($this, $method)) {
            return false;
        }
        call_user_func_array(array($this, $method), $command['params']);
        
        return true;
    }
    
    /**
     * Run custom command if exists
     * 
     * @param array $command
     * 
     * @return boolean
     */
    protected function runCustomCommand($command)
    {
        if (!CommandRegistry::hasCommand($command['command'])) {
            throw new BadMethodCallException('Command "' . $command['command'] . '" not found');
        }
        $customCommand = CommandRegistry::getCommand($command['command']);
        
        array_unshift($command['params'], $this->image);
        call_user_func_array($customCommand, $command['params']);
        
        return true;
    }
    
    /**
     * Command image thumb
     * 
     * @param int $width
     * @param int $height
     * 
     * @return void
     */
    protected function imageThumb($width, $height)
    {
        $width = (int) $width;
        $height = (int) $height;
        if ($width <= 0) {
            throw new BadMethodCallException('Invalid parameter width for command "thumb"');
        }
        if ($height <= 0) {
            throw new BadMethodCallException('Invalid parameter height for command "thumb"');
        }
        $this->image = $this->image->thumbnail(new Box($width, $height));
    }
    
    /**
     * Command image resize
     * 
     * @param int $width
     * @param int $height
     * 
     * @return void
     */
    protected function imageResize($width, $height)
    {
        $width = (int) $width;
        $height = (int) $height;
        if ($width <= 0) {
            throw new BadMethodCallException('Invalid parameter width for command "resize"');
        }
        if ($height <= 0) {
            throw new BadMethodCallException('Invalid parameter height for command "resize"');
        }
        $this->image->resize(new Box($width, $height));
    }
    
    /**
     * Command image grayscale
     * 
     * @return void
     */
    protected function imageGrayscale()
    {
        $this->image->effects()->grayscale();
    }
    
    /**
     * Command image negative
     * 
     * @return void
     */
    protected function imageNegative()
    {
        $this->image->effects()->negative();
    }
    
    /**
     * Command image gamma
     * 
     * @param float $correction
     * 
     * @return void
     */
    protected function imageGamma($correction)
    {
        $correction = (float) $correction;
        
        $this->image->effects()->gamma($correction);
    }
    
    /**
     * Command image colorize
     * 
     * @param string $hexColor
     * 
     * @return void
     */
    protected function imageColorize($hexColor)
    {
        if (strlen($hexColor) != 6 || !preg_match('![0-9abcdef]!i', $hexColor)) {
            throw new BadMethodCallException('Invalid parameter color for command "colorize"');
        }
        $color = $this->image->palette()->color('#' . $hexColor);
        
        $this->image->effects()->colorize($color);
    }
    
    /**
     * Command image sharpen
     * 
     * @return void
     */
    protected function imageSharpen()
    {
        $this->image->effects()->sharpen();
    }
    
    /**
     * Command image blur
     * 
     * @param float $sigma
     * 
     * @return void
     */
    protected function imageBlur($sigma = 1)
    {
        $sigma = (float) $sigma;
        
        $this->image->effects()->blur($sigma);
    }
}

