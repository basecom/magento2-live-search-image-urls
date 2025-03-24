<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SizeMode implements OptionSourceInterface
{
    public const MODE_AUTO = 'automatic';
    public const MODE_MANUAL = 'manual';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::MODE_AUTO, 'label' => __(ucwords(self::MODE_AUTO))],
            ['value' => self::MODE_MANUAL, 'label' => __(ucwords(self::MODE_MANUAL))],
        ];
    }
}
