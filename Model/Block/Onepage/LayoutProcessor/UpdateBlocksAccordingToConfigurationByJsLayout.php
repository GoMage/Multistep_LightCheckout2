<?php

namespace GoMage\SuperLightCheckout\Model\Block\Onepage\LayoutProcessor;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Framework\UrlInterface;

/**
 * Unset blocks according to the configurations.
 */
class UpdateBlocksAccordingToConfigurationByJsLayout
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        UrlInterface $urlBuilder
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function execute($jsLayout)
    {
        $jsLayout = $this->disableDiscountCodesAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->disableDeletingItemOnCheckoutAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->disableChangingQtyOnCheckoutAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->addSocialNetworksAccordingToTheConfiguration($jsLayout);

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableDiscountCodesAccordingToTheConfiguration($jsLayout)
    {
        $generalConfigurations = $this->checkoutConfigurationsProvider->getGeneralConfigurations();
        $isEnabledDiscountCodes = $generalConfigurations->getIsEnabledDiscountCodes();

        if (!$isEnabledDiscountCodes) {
            unset($jsLayout['components']['checkout']['children']['steps']['children']['payment-step']['children']
                ['payment']['children']['afterMethods']['children']['discount']);
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableDeletingItemOnCheckoutAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledRemoveItemFromCheckout = $this->checkoutConfigurationsProvider->getGeneralConfigurations()
            ->getIsAllowedToRemoveItemFromCheckout();

        if (!$isEnabledRemoveItemFromCheckout) {
            unset($jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
                ['children']['cart_items']['children']['details']['children']['delete_item']);
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function disableChangingQtyOnCheckoutAccordingToTheConfiguration($jsLayout)
    {
        $isEnabledChangeQty = $this->checkoutConfigurationsProvider->getGeneralConfigurations()
            ->getIsAllowedToChangeQty();

        if (!$isEnabledChangeQty) {
            unset($jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
                ['children']['cart_items']['children']['details']['children']['increase_item_qty']);
            unset($jsLayout['components']['checkout']['children']['sidebar']['children']['summary']
                ['children']['cart_items']['children']['details']['children']['decrease_item_qty']);
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function addSocialNetworksAccordingToTheConfiguration($jsLayout)
    {
        $socialLoginConfigurations = $this->checkoutConfigurationsProvider->getSocialLogin();

        if ($socialLoginConfigurations->getIsSocialLoginGoogleEnabled()) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['customer-email']['children']['social-networks']
            ['children']['google']['urlTo'] = $this->urlBuilder->getUrl(
                'superlightcheckout/social/login',
                ['type' => 'Google']
            );
        } else {
            unset($jsLayout['components']['checkout']['children']['steps']['children']
                ['shipping-address-step']['children']['shippingAddress']['children']['customer-email']
                ['children']['social-networks']
                ['children']['google']);
        }

        if ($socialLoginConfigurations->getIsSocialLoginFacebookEnabled()) {
            $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-address-step']['children']['shippingAddress']['children']['customer-email']['children']
            ['social-networks']['children']['facebook']['urlTo'] = $this->urlBuilder->getUrl(
                'superlightcheckout/social/login',
                ['type' => 'Facebook']
            );
        } else {
            unset($jsLayout['components']['checkout']['children']['steps']['children']
                ['shipping-address-step']['children']['shippingAddress']['children']['customer-email']['children']
                ['social-networks']['children']['facebook']);
        }

        if ($socialLoginConfigurations->getIsSocialLoginTwitterEnabled()) {
            $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-address-step']['children']['shippingAddress']['children']['customer-email']['children']['social-networks']
            ['children']['twitter']['urlTo'] = $this->urlBuilder->getUrl(
                'superlightcheckout/social/login',
                ['type' => 'Twitter']
            );
        } else {
            unset($jsLayout['components']['checkout']['children']['steps']['children']
                ['shipping-address-step']['children']['shippingAddress']['children']['customer-email']
                ['children']['social-networks']['children']['twitter']);
        }

        return $jsLayout;
    }
}
