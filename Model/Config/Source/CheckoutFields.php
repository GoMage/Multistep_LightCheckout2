<?php

namespace GoMage\SuperLightCheckout\Model\Config\Source;

use GoMage\SuperLightCheckout\Model\Config\AddressFieldsProvider;
use Magento\Framework\Data\OptionSourceInterface;

class CheckoutFields implements OptionSourceInterface
{
    /**#@+
     * Checkout blocks where help message can be shown.
     */
    const SHIPPING_METHODS = 1;
    const PAYMENT_METHOD = 2;
    /**#@-*/

    /**
     * @var AddressFieldsProvider
     */
    private $addressFieldsProvider;

    /**
     * @param AddressFieldsProvider $addressFieldsProvider
     */
    public function __construct(
        AddressFieldsProvider $addressFieldsProvider
    ) {
        $this->addressFieldsProvider = $addressFieldsProvider;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array_merge(
            $this->getAddressFieldsToOptionArray(),
            [
                ['value' => self::SHIPPING_METHODS, 'label' => __('Shipping Methods')],
                ['value' => self::PAYMENT_METHOD, 'label' => __('Payment Method')],
            ]
        );
    }

    /**
     * @return array
     */
    private function getAddressFieldsToOptionArray()
    {
        $options = [];
        $addressFields = $this->addressFieldsProvider->get();

        foreach ($addressFields as $addressField) {
            $options[] = [
                'value' => $addressField->getAttributeCode(),
                'label' => $addressField->getFrontendLabel(),
            ];
        }

        return ['address_fields' => ['label' => 'Address Fields', 'value' => $options]];
    }
}
