<?php

namespace GoMage\SuperLightCheckout\Plugin\Checkout;

use GoMage\Core\Helper\Data;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

class TurnOffMagentoCheckout
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param Data $helper
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        Data $helper
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->helper = $helper;
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
        if ($this->checkoutConfigurationsProvider->getGeneralConfigurations()->isEnabledSuperLightCheckout()
            && $this->helper->isA(CheckoutConfigurationsProvider::MODULE_NAME)
        ) {
            $result = false;
        }

        return $result;
    }
}
