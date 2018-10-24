<?php

namespace GoMage\SuperLightCheckout\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CheckoutMode implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Registered and guest customers')],
            ['value' => 1, 'label' => __('Only registered customers')],
            ['value' => 2, 'label' => __('Only guest customers')],
        ];
    }
}
