<?php

namespace TckImageResizer\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use TckImageResizer\Util\UrlSafeBase64;

class Resize extends AbstractHelper
{
    /** @var  array */
    protected $imgParts;
    /** @var  string*/
    protected $commands;

    /**
     * @param $imgPath
     * @return $this
     */
    public function __invoke($imgPath)
    {
        $this->imgParts = pathinfo($imgPath);
        $this->commands = '';
        
        return $this;
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     */
    public function thumb($width, $height)
    {
        $this->commands .= '$thumb,' . $width . ',' . $height;
        
        return $this;
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     */
    public function resize($width, $height)
    {
        $this->commands .= '$resize,' . $width . ',' . $height;
        
        return $this;
    }

    /**
     * @return $this
     */
    public function grayscale()
    {
        $this->commands .= '$grayscale';
        
        return $this;
    }

    /**
     * @return $this
     */
    public function negative()
    {
        $this->commands .= '$negative';
        
        return $this;
    }

    /**
     * @param $correction
     * @return $this
     */
    public function gamma($correction)
    {
        $this->commands .= '$gamma,' . $correction;
        
        return $this;
    }

    /**
     * @param $hexColor
     * @return $this
     */
    public function colorize($hexColor)
    {
        $this->commands .= '$colorize,' . $hexColor;
        
        return $this;
    }

    /**
     * @return $this
     */
    public function sharpen()
    {
        $this->commands .= '$sharpen';
        
        return $this;
    }

    /**
     * @param null $sigma
     * @return $this
     */
    public function blur($sigma = null)
    {
        $this->commands .= '$blur' . ($sigma !== null ? ',' . $sigma : '');
        
        return $this;
    }

    /**
     * @param null $text
     * @param null $backgroundColor
     * @param null $color
     * @param null $width
     * @param null $height
     * @return $this
     */
    public function x404($text = null, $backgroundColor = null, $color = null, $width = null, $height = null)
    {
        $this->commands .= '$404'
                . ($text !== null ? ',' . UrlSafeBase64::encode($text) : '')
                . ($backgroundColor !== null ? ',' . $backgroundColor : '')
                . ($color !== null ? ',' . $color : '')
                . ($width !== null ? ',' . $width : '')
                . ($height !== null ? ',' . $height : '');
        
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getView()->basePath(
            'processed/'
            . ($this->imgParts['dirname'] && $this->imgParts['dirname'] !== '.' ? $this->imgParts['dirname'] . '/' : '')
            . $this->imgParts['filename'] . '.'
            . $this->commands . '.' . $this->imgParts['extension']
        );
    }
}
