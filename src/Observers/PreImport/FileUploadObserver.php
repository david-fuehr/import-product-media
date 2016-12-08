<?php

/**
 * TechDivision\Import\Product\Media\Observers\PreImport\FileUploadObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */

namespace TechDivision\Import\Product\Media\Observers\PreImport;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * A SLSB that handles the process to import product media.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class FileUploadObserver extends AbstractProductImportObserver
{

    /**
     * {@inheritDoc}
     * @see \Importer\Csv\Actions\Listeners\Row\ListenerInterface::handle()
     */
    public function handle(array $row)
    {

        // load the header information
        $headers = $this->getHeaders();

        // query whether or not, the image changed
        if ($this->isParentImage($image = $row[$headers[ColumnKeys::IMAGE_PATH]])) {
            return $row;
        }

        // upload the image file and set the new filename
        $row[$headers[ColumnKeys::IMAGE_PATH_NEW]] = $this->uploadFile($image);

        // returns the row
        return $row;
    }

    /**
     * Upload's the file with the passed name to the Magento
     * media directory. If the file already exists, the will
     * be given a new name that will be returned.
     *
     * @param string $filename The name of the file to be uploaded
     *
     * @return string The name of the uploaded file
     */
    public function uploadFile($filename)
    {
        return $this->getSubject()->uploadFile($filename);
    }

    /**
     * Return's the name of the created image.
     *
     * @return string The name of the created image
     */
    public function getParentImage()
    {
        return $this->getSubject()->getParentImage();
    }

    /**
     * Return's TRUE if the passed image is the parent one.
     *
     * @param string $image The imageD to check
     *
     * @return boolean TRUE if the passed image is the parent one
     */
    public function isParentImage($image)
    {
        return $this->getParentImage() === $image;
    }
}