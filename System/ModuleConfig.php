<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\System;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ModuleConfig
{
    private const XML_PATH_RESIZE_MODE = 'storefront_features/image_dimensions/size_mode';
    private const XML_PATH_RESIZE_IMAGE_ID = 'storefront_features/image_dimensions/image_id';
    private const XML_PATH_RESIZE_HEIGHT = 'storefront_features/image_dimensions/resize_height';
    private const XML_PATH_RESIZE_WIDTH = 'storefront_features/image_dimensions/resize_width';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getResizeMode($scopeId = null): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RESIZE_MODE, ScopeInterface::SCOPE_STORE, $scopeId);
    }

    public function getImageId($scopeId = null): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RESIZE_IMAGE_ID, ScopeInterface::SCOPE_STORE, $scopeId);
    }

    public function getResizeHeight($scopeId = null): ?int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_RESIZE_HEIGHT, ScopeInterface::SCOPE_STORE, $scopeId)
            ?? null;
    }

    public function getResizeWidth($scopeId = null): ?int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_RESIZE_WIDTH, ScopeInterface::SCOPE_STORE, $scopeId)
            ?? null;
    }
}
