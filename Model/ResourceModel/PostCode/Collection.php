<?php

namespace GoMage\SuperLightCheckout\Model\ResourceModel\PostCode;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \GoMage\SuperLightCheckout\Model\PostCode::class,
            \GoMage\SuperLightCheckout\Model\ResourceModel\PostCode::class
        );
    }
}
