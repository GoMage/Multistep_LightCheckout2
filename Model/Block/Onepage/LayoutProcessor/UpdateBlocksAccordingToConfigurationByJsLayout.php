<?php

namespace GoMage\SuperLightCheckout\Model\Block\Onepage\LayoutProcessor;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\SuperLightCheckout\Model\Config\Source\CheckoutFields;
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
        $jsLayout = $this->updateTemplateForPostcodeFieldAccordingToTheConfiguration($jsLayout);
        $jsLayout = $this->addHelpMessagesAccordingToTheConfiguration($jsLayout);

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

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function updateTemplateForPostcodeFieldAccordingToTheConfiguration($jsLayout)
    {
        if ($this->checkoutConfigurationsProvider->getAutofillByZipCode()->getIsEnabledAutoFillByZipCode()) {
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-address-step']['children']
            ['billingAddress']['children']['billing-address-fieldset']
            ['children']['postcode']['config']['elementTmpl']
                = 'GoMage_SuperLightCheckout/element/element-with-blur-template';
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-address-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']
            ['children']['postcode']['component']
                = 'GoMage_SuperLightCheckout/js/view/post-code';

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['config']
            ['elementTmpl']
                = 'GoMage_SuperLightCheckout/element/element-with-blur-template';
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['component']
                = 'GoMage_SuperLightCheckout/js/view/post-code';
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    private function addHelpMessagesAccordingToTheConfiguration($jsLayout)
    {
        $helpMessages = $this->checkoutConfigurationsProvider->getHelpMessages()->getHelpMessages();

        if ($helpMessages) {
            $helpMessages = json_decode($helpMessages, true);

            foreach ($helpMessages as $helpMessage) {
                if (!is_numeric($helpMessage['field'])) {
                    $jsLayout = $this->addToolTipMessageForFieldByAddressType(
                        $jsLayout,
                        'billing',
                        $helpMessage['field'],
                        $helpMessage['help_message']
                    );
                    $jsLayout = $this->addToolTipMessageForFieldByAddressType(
                        $jsLayout,
                        'shipping',
                        $helpMessage['field'],
                        $helpMessage['help_message']
                    );
                } else {
                    switch ($helpMessage['field']) {
                        case CheckoutFields::SHIPPING_METHODS:
                            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-method-step']
                            ['children']['shippingMethod']['tooltip']['description'] = $helpMessage['help_message'];
                            break;
                        case CheckoutFields::PAYMENT_METHOD:
                            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
                            ['children']['payment']['children']['payments-list']
                            ['tooltip']['description'] = $helpMessage['help_message'];
                            break;
                    }
                }
            }
        }

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     * @param string $addressType
     * @param string $field
     * @param string $message
     *
     * @return array
     */
    private function addToolTipMessageForFieldByAddressType($jsLayout, $addressType, $field, $message)
    {
        $jsLayout['components']['checkout']['children']['steps']['children'][$addressType . '-address-step']['children']
        [$addressType . 'Address']['children'][$addressType . '-address-fieldset']['children']
        [$field]['config']['tooltip']['description'] = $message;

        return $jsLayout;
    }
}
