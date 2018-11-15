<?php

namespace GoMage\SuperLightCheckout\Plugin\Checkout;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

class TurnOffMagentoCheckout
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
    }

    /**
     * @param \Magento\Checkout\Helper\Data $subject
     * @param bool $result
     *
     * @return bool
     */
    public function afterCanOnepageCheckout(
        \Magento\Checkout\Helper\Data $subject,
        $result
    ) {
        if ($this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()) {
            $result = false;
        }

        return $result;
    }
}
