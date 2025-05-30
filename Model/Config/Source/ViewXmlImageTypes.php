<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\StdLib\ArrayManager;
use Magento\Framework\View\ConfigInterface;

class ViewXmlImageTypes implements OptionSourceInterface
{
    public function __construct(
        private readonly ArrayManager    $arrayManager,
        private readonly ConfigInterface $viewConfig,
        private readonly string $imageType = 'thumbnail'
    ) {
    }

    public function toOptionArray(): array
    {
        $imageTypes = [];
        $viewConfig = $this->viewConfig->getViewConfig(['area' => 'frontend'])->read() ?? [];
        $imageConfig = $this->arrayManager->get('media/Magento_Catalog/images', $viewConfig) ?? [];

        foreach ($imageConfig as $type => $config) {
            if (!isset($config['type']) || $config['type'] !== $this->imageType) {
                continue;
            }

            $suffix = '';

            if (isset($config['width'])) {
                $widthSuffix = 'W: ' . $config['width'] . 'px';
                $heightSuffix = isset($config['height'])
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
