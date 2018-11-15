<?php

namespace GoMage\SuperLightCheckout\Api;

use Magento\Quote\Api\Data\TotalsItemInterface;

interface GuestQuoteItemManagementInterface
{
    /**
     * @param string $cartId
     * @param TotalsItemInterface $item
     *
     * @return \GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface
     */
    public function updateItemQty($cartId, TotalsItemInterface $item);

    /**
     * @param string $cartId
     * @param int $itemId
     *
     * @return \GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface
     */
    public function removeItemById($cartId, $itemId);

    /**
     * @param string $cartId
     *
     * @return \GoMage\SuperLightCheckout\Model\QuoteItemManagement\ResponseDataInterface
     */
    public function getActiveQuoteInformation($cartId);

}
