<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\Plugin;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogDataExporter\Model\Provider\Product\Formatter\ImageFormatter;

class SetCachedImageUrls
{
    /**
     * @var Product|null
     */
    private ?Product $product = null;

    /**
     * @param ImageHelper $imageHelper
     * @param ProductFactory $productFactory
     */
    public function __construct(
        private readonly ImageHelper    $imageHelper,
        private readonly ProductFactory $productFactory
    ) {
    }

    /**
     * Retrieve the product used to initialize the ImageHelper
     *
     * @return Product
     */
    private function getProduct(): Product
    {
        if (!$this->product instanceof Product) {
            $this->product = $this->productFactory->create();
        }
        return $this->product;
    }

    /**
     * Replace the thumbnail url for products in Live Search with a resized cached version
     *
     * @param ImageFormatter $subject
     * @param array $result
     * @param array $row
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterFormat(ImageFormatter $subject, array $result, array $row): array
    {
        $imageFilePath = $row['thumbnail'] ?? null;

        if (!$imageFilePath) {
            return $result;
        }

        $result['thumbnail']['url'] = $this->getResizedImageUrl($imageFilePath);

        return $result;
    }

    /**
     * Retrieve resized image url for a given image file
     *
     * @param string $imageFile
     * @return string
     */
    private function getResizedImageUrl(string $imageFile): string
    {
        $imageHelper = $this->imageHelper->init($this->getProduct(), 'product_page_image_small');

        /**
         * Check for empty images and use the default placeholder.
         * Make sure that the provided placeholder source image in the admin area is properly resized.
         */
        if (!$imageFile || $imageFile === 'no_selection') {
            return $imageHelper->getDefaultPlaceholderUrl('thumbnail');
        }

        /**
         * Resize image to the default 90 x 90 px dimensions provided in the view.xml file
         */
        return $imageHelper->setImageFile($imageFile)->resize(90, 90)->getUrl();
    }
}
