<?php

namespace GoMage\SuperLightCheckout\Api;

use Magento\Quote\Api\Data\TotalsItemInterface;

interface QuoteItemManagementInterface
{
    /**
     * @param int $cartId
     * @param TotalsItemInterface $item
     *
     * @return \GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface
     */
    public function updateItemQty($cartId, TotalsItemInterface $item);

    /**
     * @param int $cartId
     * @param int $itemId
     *
     * @return \GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface
     */
    public function removeItemById($cartId, $itemId);

    /**
     * @param int|null $cartId
     *
     * @return \GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface
     */
    public function getActiveQuoteInformation($cartId = null);
}
