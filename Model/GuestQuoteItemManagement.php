<?php

namespace GoMage\SuperLightCheckout\Model;

use GoMage\SuperLightCheckout\Api\GuestQuoteItemManagementInterface;
use GoMage\SuperLightCheckout\Api\QuoteItemManagementInterface;
use Magento\Quote\Api\Data\TotalsItemInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

class GuestQuoteItemManagement implements GuestQuoteItemManagementInterface
{
    /**
     * @var QuoteItemManagementInterface
     */
    private $quoteItemManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param QuoteItemManagementInterface $quoteItemManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        QuoteItemManagementInterface $quoteItemManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->quoteItemManagement = $quoteItemManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    public function updateItemQty($cartId, TotalsItemInterface $item)
    {
        /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->quoteItemManagement->updateItemQty($quoteIdMask->getQuoteId(), $item);
    }

    /**
     * @inheritdoc
     */
    public function removeItemById($cartId, $itemId)
    {
        /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->quoteItemManagement->removeItemById($quoteIdMask->getQuoteId(), $itemId);
    }

    /**
     * @inheritdoc
     */
    public function getActiveQuoteInformation($cartId)
    {
        /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->quoteItemManagement->getActiveQuoteInformation($quoteIdMask->getQuoteId());
    }
}
