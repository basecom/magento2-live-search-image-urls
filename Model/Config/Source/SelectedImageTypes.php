<?php

namespace Basecom\LiveSearchImageUrls\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SelectedImageTypes implements OptionSourceInterface
{
    public const IMAGE_TYPE_NONE = 'none';
    public const IMAGE_TYPE_THUMBNAIL = 'thumbnail';
    public const IMAGE_TYPE_IMAGE = 'image';
    public const IMAGE_TYPE_SMALL_IMAGE = 'small_image';
    public const IMAGE_TYPE_SWATCH_IMAGE = 'swatch_image';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::IMAGE_TYPE_NONE, 'label' =>
                __(ucwords(self::IMAGE_TYPE_NONE))],
            ['value' => self::IMAGE_TYPE_THUMBNAIL, 'label' =>
                __(ucwords(self::IMAGE_TYPE_THUMBNAIL))],
            ['value' => self::IMAGE_TYPE_IMAGE, 'label' =>
                __(ucwords(self::IMAGE_TYPE_IMAGE))],
            ['value' => self::IMAGE_TYPE_SMALL_IMAGE, 'label' =>
                __(ucwords(str_replace('_', ' ', self::IMAGE_TYPE_SMALL_IMAGE)))],
            ['value' => self::IMAGE_TYPE_SWATCH_IMAGE, 'label' =>
                __(ucwords(str_replace('_', ' ', self::IMAGE_TYPE_SWATCH_IMAGE)))],
        ];
    }
}
