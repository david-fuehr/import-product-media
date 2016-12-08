<?php

/**
 * TechDivision\Import\Product\Media\Actions\Processors\Batch\ProductMediaGalleryValueToEntityPersistBatchProcessor
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

namespace TechDivision\Import\Product\Bundle\Actions\Processors\Batch;

use TechDivision\Import\Product\Media\Utils\SqlStatements;
use TechDivision\Import\Actions\Processors\Batch\AbstractPersistBatchProcessor;

/**
 * The product media gallery value to entity persist batch processor implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class ProductMediaGalleryValueToEntityPersistBatchProcessor extends AbstractPersistBatchProcessor
{

    /**
     * {@inheritDoc}
     * @see \TechDivision\Import\Actions\Processors\Batch\AbstractPersistBatchProcessor::getNumberOfPlaceholders()
     */
    protected function getNumberOfPlaceholders()
    {
        return 2;
    }

    /**
     * {@inheritDoc}
     * @see \TechDivision\Import\Actions\Processors\Batch\AbstractPersistBatchProcessor::getStatement()
     */
    protected function getStatement()
    {
        $utilityClassName = $this->getUtilityClassName();
        return $utilityClassName::CREATE_PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY;
    }
}