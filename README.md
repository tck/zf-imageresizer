## ImageResizer

[![Latest Stable Version](https://poser.pugx.org/tck/zf2-imageresizer/v/stable)](https://packagist.org/packages/tck/zf2-imageresizer)
[![Build Status](https://travis-ci.org/tck/zf-imageresizer.svg?branch=master)](https://travis-ci.org/tck/zf-imageresizer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tck/zf2-imageresizer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tck/zf2-imageresizer/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tck/zf2-imageresizer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tck/zf2-imageresizer/?branch=master)
[![Total Downloads](https://poser.pugx.org/tck/zf2-imageresizer/downloads)](https://packagist.org/packages/tck/zf2-imageresizer)
[![License](https://poser.pugx.org/tck/zf2-imageresizer/license)](https://packagist.org/packages/tck/zf2-imageresizer)

This Laminas module, once enabled, allows image resizing and manipulation by url.


### Requirements

* PHP 7.2+
* [Laminas MVC](https://docs.laminas.dev/mvc/)
* [Imagine](http://imagine.readthedocs.org/en/latest/)

> **IMPORTANT! Version notes**
> * Version **3.x**: Laminas, dropped support for Zend Framework 3.
> * Version **2.x**: Zend Framework 3, dropped support for Zend Framework 2.
> * Version **1.x**: Zend Framework 2

### Installation

Install via composer in your Laminas project

    $ composer require tck/zf2-imageresizer


### Post installation

1. Enabling it in your `application.config.php` file.

    ```php
    <?php
    return [
        'modules' => ]
            // ...
            'TckImageResizer',
        ],
        // ...
    ];
    ```

2. Create "processed" folder in "public" folder.

### Usage

#### Basic Syntax

All files in public folder

* folder/filename.ext
* `processed/`folder/filename`.$command1,param1,param2$command2`.ext

Example: Create a thumbnail and grayscale image

* img/logo.jpg
* `processed/`img/logo`.$thumb,160,120$grayscale`.jpg

#### View helper
Laminas tempalte:

```php
<img alt="Example image" src="<?php echo $this->resize('img/logo.jpg')->thumb(200, 160)->grayscale(); ?>" />
```

Rendered HTML:

```php
<img alt="Example image" src="/processed/img/logo.$thumb,200,160$grayscale.jpg" />
```


#### Command list

* thumb(width, height)
* resize(width, height)
* grayscale
* negative
* gamma(correction)
* colorize(hexColor)
* sharpen
* blur(sigma = 1)
* 404(text = 'Not found', backgroundColor = 'F8F8F8', color = '777777', width = null, height = null)
	In view helper: ->x404(...)
	[text: url-safe base64] - \TckImageResizer\Util\UrlSafeBase64::encode($text)

Own commands possible - example place a watermark (Todo Documentation)


### Goals / Todos

* More commands
* More command options
* Administrative functions
* Create placeholder
