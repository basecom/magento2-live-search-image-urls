<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\StdLib\ArrayManager;
use Magento\Framework\View\ConfigInterface;

class ViewXmlImageTypes implements OptionSourceInterface
{
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly ConfigInterface $viewConfig
    ) {
    }

    public function toOptionArray(): array
    {
        $imageTypes = [];
        $viewConfig = $this->viewConfig->getViewConfig(['area' => 'frontend'])->read() ?? [];
        $imageConfig = $this->arrayManager->get('media/Magento_Catalog/images', $viewConfig) ?? [];

        foreach ($imageConfig as $type => $config) {
            $suffix = '';

            if (array_key_exists('width', $config)) {
                $widthSuffix = 'W: ' . $config['width'] . 'px';
                $heightSuffix = array_key_exists('height', $config)
                    ? 'H: ' . $config['height'] . 'px'
                    : 'H: auto';

                $suffix = sprintf('(%s, %s)', $widthSuffix, $heightSuffix);
            }

            $imageTypes[] = [
                'value' => $type,
                'label' => ucwords(str_replace('_', ' ', $type)) . $suffix,
            ];
        }

        return $imageTypes;
    }
}
