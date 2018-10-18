<?php

namespace GoMage\SuperLightCheckout\Model\Block\Onepage\LayoutProcessor;

use GoMage\SuperLightCheckout\Model\Layout\FetchArgs;

class MergeComponentFromMagentoCheckout
{
    /**
     * @var FetchArgs
     */
    private $fetchArgs;

    /**
     * @param FetchArgs $fetchArgs
     */
    public function __construct(FetchArgs $fetchArgs)
    {
        $this->fetchArgs = $fetchArgs;
    }

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function execute($jsLayout)
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['renders']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['renders']['children'],
            'renders'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['additional-payment-validators']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['additional-payment-validators']['children'],
            'additional-payment-validators'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['beforeMethods']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['beforeMethods']['children'],
            'beforeMethods'
        );
        
        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['payments-list']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['payments-list']['children'],
            'payments-list'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['afterMethods']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['afterMethods']['children'],
            'afterMethods'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
        ['children']['shippingAddress']['children']['before-form']['children']
            = $this->mergeShippingChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['before-form']['children'],
            'before-form'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
        ['children']['shippingAddress']['children']['before-fields']['children']
            = $this->mergeShippingChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['before-fields']['children'],
            'before-fields'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
        ['children']['shippingAddress']['children']['address-list-additional-addresses']['children']
            = $this->mergeShippingChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['address-list-additional-addresses']['children'],
            'address-list-additional-addresses'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-method-step']
        ['children']['before-shipping-method-form']['children']
            = $this->mergeShippingChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-method-step']
            ['children']['before-shipping-method-form']['children'],
            'before-shipping-method-form'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-method-step']
        ['children']['shippingMethod']['children']['price']
            = $this->getPriceFromShippingAddressToShippingMethod();
        
        return $jsLayout;
    }

    /**
     * @return array
     */
    private function getPriceFromShippingAddressToShippingMethod()
    {
        $path = '//referenceBlock[@name="checkout.root"]/arguments/argument[@name="jsLayout"]'
            . '/item[@name="components"]/item[@name="checkout"]/item[@name="children"]'
            . '/item[@name="steps"]/item[@name="children"]/item[@name="shipping-step"]'
            . '/item[@name="children"]/item[@name="shippingAddress"]/item[@name="children"]'
            . '/item[@name="price"]';

        $args = $this->fetchArgs->execute('checkout_index_index', $path);

        return $args;
    }

    /**
     * @param array $layout
     * @param string $elementName
     *
     * @return array
     */
    private function mergePaymentChildElement($layout, $elementName)
    {
        $path = '//referenceBlock[@name="checkout.root"]/arguments/argument[@name="jsLayout"]'
            . '/item[@name="components"]/item[@name="checkout"]/item[@name="children"]'
            . '/item[@name="steps"]/item[@name="children"]/item[@name="billing-step"]'
            . '/item[@name="children"]/item[@name="payment"]/item[@name="children"]'
            . '/item[@name="' . $elementName . '"]/item[@name="children"]';

        $args = $this->fetchArgs->execute('checkout_index_index', $path);

        return array_merge($args, $layout);
    }

    /**
     * @param array $layout
     * @param string $elementName
     *
     * @return array
     */
    private function mergeShippingChildElement($layout, $elementName)
    {
        $path = '//referenceBlock[@name="checkout.root"]/arguments/argument[@name="jsLayout"]'
            . '/item[@name="components"]/item[@name="checkout"]/item[@name="children"]'
            . '/item[@name="steps"]/item[@name="children"]/item[@name="shipping-address-step"]'
            . '/item[@name="children"]/item[@name="shippingAddress"]/item[@name="children"]'
            . '/item[@name="' . $elementName . '"]/item[@name="children"]';

        $args = $this->fetchArgs->execute('checkout_index_index', $path);

        return array_merge($args, $layout);
    }
}
