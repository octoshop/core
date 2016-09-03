<?php namespace Octoshop\Core\Util;

use System\Models\File;

class Image
{
    /**
     * @var int Default image width
     */
    const IMG_DEFAULT_WIDTH = 300;

    /**
     * @var int Default image height
     */
    const IMG_DEFAULT_HEIGHT = 0;

    /**
     * @var int Default image mode
     */
    const IMG_DEFAULT_MODE = 'crop';

    /**
     * @var int Default image extension
     */
    const IMG_DEFAULT_FORMAT = 'auto';

    /**
     * @var int Default image quality
     */
    const IMG_DEFAULT_QUALITY = 95;

    /**
     * @var array List of supported image modes
     */
    protected static $image_modes = ['auto', 'exact', 'portrait', 'landscape', 'crop'];

    /**
     * @var array List of supported image file extensions
     */
    protected static $image_extensions = ['auto', 'gif', 'jpg', 'png'];

    /**
     * Generate a thumbnail URL for a given image
     * @param System\Models\File $image
     * @param int                $width
     * @param int                $height
     * @param string             $mode
     * @param int                $quality
     * @param string             $extension
     */
    public static function thumbnail(
        File $image = null,
        $width = self::IMG_DEFAULT_WIDTH,
        $height = self::IMG_DEFAULT_HEIGHT,
        $mode = self::IMG_DEFAULT_MODE,
        $extension = self::IMG_DEFAULT_FORMAT,
        $quality = self::IMG_DEFAULT_QUALITY
    ) {
        if ((int) $width !== $width || $width < 0) {
            $width = self::IMG_DEFAULT_WIDTH;
        }

        if ((int) $height !== $height || $height < 0) {
            $height = self::IMG_DEFAULT_HEIGHT;
        }

        if (!in_array($mode, self::$image_modes)) {
            $mode = self::IMG_DEFAULT_MODE;
        }

        if (!in_array($extension, self::$image_extensions)) {
            $extension = self::IMG_DEFAULT_FORMAT;
        }

        if ((int) $quality !== $quality || $quality < 0 || $quality > 100) {
            $quality = self::IMG_DEFAULT_QUALITY;
        }

        if (!$image) {
            $dimensions = $width.'x'.($height ?: $width);
            $format = $extension == 'auto' ? 'png' : $extension;

            return "http://octoshop.co/api/placeholder/{$dimensions}.{$format}";
        }

        return $image->getThumb($width, $height ?: $width, compact('mode', 'extension', 'quality'));
    }
}
