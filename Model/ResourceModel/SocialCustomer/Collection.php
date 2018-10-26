<?php

namespace GoMage\SuperLightCheckout\Model\ResourceModel\SocialCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \GoMage\SuperLightCheckout\Model\SocialCustomer::class,
            \GoMage\SuperLightCheckout\Model\ResourceModel\SocialCustomer::class
        );
    }
}
