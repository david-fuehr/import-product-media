<?php

/**
 * TechDivision\Import\Product\Media\Actions\Processors\ProductMediaGalleryPersistProcessor
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

namespace TechDivision\Import\Product\Media\Actions\Processors;

use TechDivision\Import\Actions\Processors\AbstractPersistProcessor;

/**
 * The product media gallery persist processor implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class ProductMediaGalleryPersistProcessor extends AbstractPersistProcessor
{

    /**
     * {@inheritDoc}
     * @see \TechDivision\Import\Actions\Processors\AbstractPersistProcessor::getStatement()
     */
    protected function getStatement()
    {
        $utilityClassName = $this->getUtilityClassName();
        return $utilityClassName::CREATE_PRODUCT_MEDIA_GALLERY;
    }

    /**
     * Persist's the passed row.
     *
     * @param array $row The row to persist
     *
     * @return string The last inserted ID
     */
    public function execute($row)
    {
        $this->getPreparedStatement()->execute($row);
        return $this->getConnection()->lastInsertId();
    }
}