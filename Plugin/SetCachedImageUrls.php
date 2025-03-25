<?php
declare(strict_types=1);

namespace Basecom\LiveSearchImageUrls\Plugin;

use Basecom\LiveSearchImageUrls\Model\Config\Source\SizeMode;
use Basecom\LiveSearchImageUrls\System\ModuleConfig;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogDataExporter\Model\Provider\Product\Formatter\ImageFormatter;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\View\ConfigInterface;

class SetCachedImageUrls
{
    /**
     * @var Product|null
     */
    private ?Product $product = null;
    private array $frontendViewConfig = [];

    /**
     * @param ModuleConfig $moduleConfig
     * @param ImageHelper $imageHelper
     * @param ProductFactory $productFactory
     * @param ArrayManager $arrayManager
     * @param ConfigInterface $viewConfig
     * @param int|null $fallbackHeight
     * @param int|null $fallbackWidth
     */
    public function __construct(
        private readonly ModuleConfig    $moduleConfig,
        private readonly ImageHelper     $imageHelper,
        private readonly ProductFactory  $productFactory,
        private readonly ArrayManager    $arrayManager,
        private readonly ConfigInterface $viewConfig,
        private readonly ?int $fallbackHeight = null,
        private readonly ?int $fallbackWidth = null,
    ) {
    }

    /**
     * Retrieve the product used to initialize the ImageHelper
     *
     * The image helper uses the product to retrieve the correct label of an image.
     * As the label is already correctly set at this point, this information is not necessary.
     * Thus, an "empty" product is used, instead of retrieving each product from the repository.
     * This drastically improves performance by avoiding database calls in a foreach-loop.
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

        $imageDimensions = $this->getImageDimensions();

        return $imageHelper->setImageFile($imageFile)
            ->resize($imageDimensions['width'], $imageDimensions['height'])
            ->getUrl();
    }

    private function getImageDimensions(): array
    {
        if ($this->moduleConfig->getResizeMode() === SizeMode::MODE_MANUAL) {
            return [
                'height' => $this->moduleConfig->getResizeHeight(),
                'width' => $this->moduleConfig->getResizeWidth(),
            ];
        }

        if ($imageId = $this->moduleConfig->getImageId()) {
            $imageConfig = $this->arrayManager->get(
                sprintf('media/Magento_Catalog/images/%s', $imageId),
                $this->getFrontendViewConfig()
            ) ?? [];

            return [
                'height' => $this->arrayManager->get('height', $imageConfig),
                'width' => $this->arrayManager->get('width', $imageConfig),
            ];
        }

        return [
            'height' => $this->fallbackHeight,
            'width' => $this->fallbackWidth,
        ];
    }

    private function getFrontendViewConfig(): array
    {
        if (!$this->frontendViewConfig) {
            $this->frontendViewConfig = $this->viewConfig->getViewConfig(['area' => 'frontend'])->read() ?? [];
        }

        return $this->frontendViewConfig;
    }
}
