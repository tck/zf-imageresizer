<?php
/**
 * Url safe base64
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2017 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizer\Util;

/**
 * Url safe base64 implementation
 * 
 * @package TckImageResizer
 */
class UrlSafeBase64
{
    /**
     * base64 encode
     *
     * @param string $data data to encode
     * @return string encoded data
     */
    public static function encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * base64 decode
     *
     * @param string $data data to decode
     * @return string decoded data
     */
    public static function decode($data)
    {
        $rData = strtr($data, '-_', '+/');
        $rMod4 = strlen($rData) % 4;
        if ($rMod4) {
            $rData .= substr('====', $rMod4);
        }
        return base64_decode($rData);
    }
    
    /**
     * base64 check string
     *
     * @param string $data data to check
     * @return boolean vaild or not
     */
    public static function valid($data)
    {
        return (boolean) preg_match('!^[a-zA-Z0-9\-_]*$!', $data);
    }
}
