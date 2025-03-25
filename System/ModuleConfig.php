<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\System;

use Basecom\LiveSearchImageUrls\Model\Config\Source\SelectedImageTypes;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ModuleConfig
{
    private const XML_PATH_ACTIVE_IMAGE_TYPES = 'storefront_features/resize_images/image_types';
    private const XML_PATH_RESIZE_MODE = 'storefront_features/resize_images/resize_mode';
    private const XML_PATH_RESIZE_IMAGE_ID = 'storefront_features/resize_images/%s/image_id';
    private const XML_PATH_RESIZE_HEIGHT = 'storefront_features/resize_images/%s/resize_height';
    private const XML_PATH_RESIZE_WIDTH = 'storefront_features/resize_images/%s/resize_width';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getResizeMode($scopeId = null): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RESIZE_MODE, ScopeInterface::SCOPE_STORE, $scopeId);
    }

    public function getResizeImageTypes($scopeId = null): array
    {
        $resizeImageTypes = $this->scopeConfig->getValue(self::XML_PATH_ACTIVE_IMAGE_TYPES, ScopeInterface::SCOPE_STORE, $scopeId) ?? 'none';
        return $resizeImageTypesArray = explode(',', $resizeImageTypes);
    }

    public function getImageId($imageType, $scopeId = null): ?string
    {
        $configPath = sprintf(self::XML_PATH_RESIZE_IMAGE_ID, $imageType);
        return $this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE, $scopeId);
    }

    public function getResizeHeight($imageType, $scopeId = null): ?int
    {
        $configPath = sprintf(self::XML_PATH_RESIZE_HEIGHT, $imageType);
        return (int)$this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE, $scopeId)
            ?? null;
    }

    public function getResizeWidth($imageType, $scopeId = null): ?int
    {
        $configPath = sprintf(self::XML_PATH_RESIZE_WIDTH, $imageType);
        return (int)$this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE, $scopeId)
            ?? null;
    }
}
