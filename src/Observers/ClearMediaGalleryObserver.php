<?php

/**
 * TechDivision\Import\Product\Media\Observers\ClearMediaGalleryObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface;

/**
 * Observer that cleaned up a product's media gallery information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class ClearMediaGalleryObserver extends AbstractProductImportObserver
{

    /**
     * The product media processor instance.
     *
     * @var \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface
     */
    protected $productMediaProcessor;

    /**
     * Initialize the observer with the passed product media processor instance.
     *
     * @param \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface $productMediaProcessor The product media processor instance
     */
    public function __construct(ProductMediaProcessorInterface $productMediaProcessor)
    {
        $this->productMediaProcessor = $productMediaProcessor;
    }

    /**
     * Return's the product media processor instance.
     *
     * @return \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface The product media processor instance
     */
    protected function getProductMediaProcessor()
    {
        return $this->productMediaProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // initialize the array for the actual images
        $actualImageNames = array();

        // iterate over the available image fields
        foreach (array_keys($this->getImageTypes()) as $imageColumnName) {
            if ($this->hasValue($imageColumnName) && !in_array($imageName = $this->getValue($imageColumnName), $actualImageNames)) {
                $actualImageNames[] = $imageName;
            }
        }

        // query whether or not, we've additional images
        if ($additionalImages = $this->getValue(ColumnKeys::ADDITIONAL_IMAGES, null, array($this, 'explode'))) {
            foreach ($additionalImages as $additionalImageName) {
                // do nothing if the image has already been added, e. g. it is the base image
                if (in_array($additionalImageName, $actualImageNames)) {
                    continue;
                }

                // else, add the image to the array
                $actualImageNames[] = $additionalImageName;
            }
        }

        // load the existing media gallery entities for the produdct with the given SKU
        $existingProductMediaGalleries = $this->getProductMediaProcessor()
                                              ->getProductMediaGalleriesBySku($sku = $this->getValue(ColumnKeys::SKU));

        // remove the images that are NOT longer available in the CSV file
        foreach ($existingProductMediaGalleries as $existingProductMediaGallery) {
            // do nothing if the file still exists
            if (in_array($existingImageName = $existingProductMediaGallery[MemberNames::VALUE], $actualImageNames)) {
                continue;
            }

            try {
                // remove the old image from the database
                $this->getProductMediaProcessor()
                     ->deleteProductMediaGallery(array(MemberNames::VALUE_ID => $existingProductMediaGallery[MemberNames::VALUE_ID]));

                // log a debug message that the image has been removed
                $this->getSubject()
                     ->getSystemLogger()
                     ->debug(sprintf('Successfully removed image "%s" from media gallery for product with SKU "%s"', $existingImageName, $sku));

            } catch (\Exception $e) {
                // log a warning if debug mode has been enabled and the file is NOT available
                if ($this->getSubject()->isDebugMode()) {
                    $this->getSubject()
                         ->getSystemLogger()
                         ->warning($this->getSubject()->appendExceptionSuffix($e->getMessage()));
                } else {
                    throw $e;
                }
            }
        }

        // log a message that the images has been cleaned-up
        $this->getSubject()
             ->getSystemLogger()
             ->debug(sprintf('Successfully cleaned-up media gallery for product with SKU "%s"', $sku));
    }

    /**
     * Load's the product media gallery entities with the passed SKU.
     *
     * @param string $sku The SKU to load the media gallery entities for
     *
     * @return array The product media gallery entities
     */
    protected function getProductMediaGalleriesBySku($sku)
    {
        return $this->getProductMediaProcessor()->getProductMediaGalleriesBySku($sku);
    }

    /**
     * Return's the array with the available image types and their label columns.
     *
     * @return array The array with the available image types
     */
    protected function getImageTypes()
    {
        return $this->getSubject()->getImageTypes();
    }
}