<?php

namespace GoMage\SuperLightCheckout\Api;

interface CheckoutAdditionalManagementInterface
{
    /**
     * @param string[] $additionInformation
     *
     * @return bool
     */
    public function saveAdditionalInformation($additionInformation);
}
