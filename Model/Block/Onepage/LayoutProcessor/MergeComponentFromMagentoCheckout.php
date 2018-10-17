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
        ['children']['payment']['children']['renders']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['renders']['children'],
            'additional-payment-validators'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['renders']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['renders']['children'],
            'beforeMethods'
        );
        
        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['renders']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['renders']['children'],
            'payments-list'
        );

        $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
        ['children']['payment']['children']['renders']['children']
            = $this->mergePaymentChildElement(
            $jsLayout['components']['checkout']['children']['steps']['children']['payment-step']
            ['children']['payment']['children']['renders']['children'],
            'afterMethods'
        );
        
        return $jsLayout;
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
}
