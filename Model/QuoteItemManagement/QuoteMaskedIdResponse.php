<?php

namespace GoMage\SuperLightCheckout\Model\QuoteItemManagement;

class QuoteMaskedIdResponse extends \Magento\Framework\DataObject implements QuoteMaskedIdResponseInterface
{
    /**
     * @inheritdoc
     */
    public function getQuoteMaskedId()
    {
        return $this->getData('quote_masked_id');
    }

    /**
     * @inheritdoc
     */
    public function setQuoteMaskedId($maskedId)
    {
        $this->setData('quote_masked_id', $maskedId);
    }
}
