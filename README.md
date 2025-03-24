# Basecom_LiveSearchImageUrls Module

<div align="center">

[![Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
![Supported Magento Versions][ico-compatibility]

</div>

---

When preparing the data for the catalog export to Live Search, the image urls see do not consider the resized, cached files.
Instead, the unchanged source file (selected in the admin area) is used. This has considerable performance implications, because the images are now a lot bigger than they need to be.
Depending on the implementation, this can affect the entire catalog and product listing page, but will in any case affect the search popover.

This module adds a plugin to the `ImageFormatter::format()` method, which correctly resizes the thumbnails for the search popover.
This fixes the [issue](https://gitlab.hyva.io/hyva-enterprise/sensei/magento2-ee-magento-live-search/-/issues/9) opened in the Hyva-Enterprise repository
(You may need to be given access to view the issue). 

In the future, the other image types (`image` and `smallImage`) will be supported as well.

## Installation

1. Install the module via composer

    ```console
    composer require basecom/magento2-live-search-image-urls
    ```

2. Enable the module

    ```console
    bin/magento module:enable Basecom_LiveSearchImageUrls
    bin/magento setup:upgrade
    ```

## Configuration

There is no configuration available (yet). In the future, this module will be extended by a configuration to allow for resizing other relevant images as well, namely:
* image
* smallImage

For now, it only supports resizing of the thumbnail image. 

## Testing


1. Reset the `catalog_data_exporter_products` index, as it might be occupied by another process.

    ```console
    bin/magento indexer:reset catalog_data_exporter_products
    ```
   
2. Initiate a partial reindex.

    ```console
    bin/magento indexer:reindex catalog_data_exporter_products
    ```
   
As the `catalog_data_exporter_products` index depends on others, this will reindex the following indexers as well:
* `catalog_category_product`
* `catalog_product_category`
* `cataloginventory_stock`

After the reindex has finished, it may take some time for Live Search to sync the data. If you want to immediately verify if the generated url is correct,
you could add a `dump($result['thumbnail']['url']);` statement to the `afterFormat()` function before returning.

> [!NOTE]
> Please make sure that the selected default image for the `thumbnail` is already correctly resized. Resizing this image programmatically may lead to incorrect results.
> You can change it under `Stores > Configuration > Catalog > Catalog > Product Image Placeholders > Thumbnail`.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email <magento@basecom.de> instead of using the issue tracker.

## License

Licensed under the [MIT](LICENSE) license.

## Copyright

basecom GmbH & Co. KG

[ico-version]: https://img.shields.io/packagist/v/basecom/magento2-live-search-image-urls.svg?style=flat-square

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[ico-compatibility]: https://img.shields.io/badge/magento-2.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square

[link-packagist]: https://packagist.org/packages/basecom/magento2-live-search-image-urls
