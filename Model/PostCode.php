<?php

namespace GoMage\SuperLightCheckout\Model;

class PostCode extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\GoMage\SuperLightCheckout\Model\ResourceModel\PostCode::class);
    }
}
