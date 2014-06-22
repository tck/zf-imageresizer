## TckImageResizer

[![Build Status](https://travis-ci.org/tck/zf2-imageresizer.svg?branch=master)](https://travis-ci.org/tck/zf2-imageresizer)
[![Latest Stable Version](https://poser.pugx.org/tck/zf2-imageresizer/v/stable.png)](https://packagist.org/packages/tck/zf2-imageresizer)
[![Total Downloads](https://poser.pugx.org/tck/zf2-imageresizer/downloads.png)](https://packagist.org/packages/tck/zf2-imageresizer)
[![License](https://poser.pugx.org/tck/zf2-imageresizer/license.png)](https://packagist.org/packages/tck/zf2-imageresizer)

This ZF2 module, once enabled, allows image resizing and manipulation by url.


### Requirements

* PHP 5.3+
* [Zend Framework 2](https://github.com/zendframework/zf2) (> 2.0)
* [Imagine](http://imagine.readthedocs.org/en/latest/) (> 0.5)


### Installation via Composer

Define dependencies in your composer.json file

```json
{
	"require": {
        "tck/zf2-imageresizer": "1.*"
    }
}
```


### Post installation

1. Enabling it in your `application.config.php` file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'TckImageResizer',
        ),
        // ...
    );
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


#### Command list

* thumb(width, height)
* resize(width, height)
* grayscale
* negative
* gamma(correction)
* colorize(hexColor)
* sharpen
* blur(sigma = 1)

Own commands possible - example place a watermark (Todo Documentation)


### Goals / Todos

* View Helper
* More commands
* More command options
* Administrative functions
* ...
