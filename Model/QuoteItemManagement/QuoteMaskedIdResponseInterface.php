<?php

namespace GoMage\SuperLightCheckout\Model\QuoteItemManagement;

interface QuoteMaskedIdResponseInterface
{
    /**
     * @return string
     */
    public function getQuoteMaskedId();

    /**
     * @param string $maskedId
     *
     * @return $this
     */
    public function setQuoteMaskedId($maskedId);
}
