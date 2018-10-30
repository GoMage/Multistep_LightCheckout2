<?php
namespace GoMage\SuperLightCheckout\Model\Config\Source\AutofillByZipCode;

class Mode
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'Google', 'label' => __('Google')],
            ['value' => 'TargetLock', 'label' => __('TargetLock')]
        ];
    }
}
