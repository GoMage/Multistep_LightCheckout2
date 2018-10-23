<?php

namespace GoMage\SuperLightCheckout\Block\Onepage;

use GoMage\SuperLightCheckout\Model\Block\Onepage\LayoutProcessor\MergeComponentFromMagentoCheckout;
use GoMage\SuperLightCheckout\Model\Block\Onepage\LayoutProcessor\UpdateBlocksAccordingToConfigurationByJsLayout;
use Magento\Checkout\Block\Checkout\AttributeMerger;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Customer\Model\Options;
use Magento\Shipping\Model\Config;
use Magento\Store\Api\StoreResolverInterface;

/**
 * Class LayoutProcessor
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var \Magento\Ui\Component\Form\AttributeMapper
     */
    private $attributeMapper;

    /**
     * @var AttributeMerger
     */
    private $merger;

    /**
     * @var Options
     */
    private $options;

    /**
     * @var StoreResolverInterface
     */
    private $storeResolver;

    /**
     * @var Config
     */
    private $shippingConfig;

    /**
     * @var MergeComponentFromMagentoCheckout
     */
    private $mergeComponentFromMagentoCheckout;

    /**
     * @var UpdateBlocksAccordingToConfigurationByJsLayout
     */
    private $updateBlocksAccordingToConfigurationByJsLayout;

    /**
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     * @param AttributeMerger $merger
     * @param Config $shippingConfig
     * @param StoreResolverInterface $storeResolver
     * @param Options $options
     * @param MergeComponentFromMagentoCheckout $mergeComponentFromMagentoCheckout
     * @param UpdateBlocksAccordingToConfigurationByJsLayout $updateBlocksAccordingToConfigurationByJsLayout
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        AttributeMerger $merger,
        Config $shippingConfig,
        StoreResolverInterface $storeResolver,
        Options $options,
        MergeComponentFromMagentoCheckout $mergeComponentFromMagentoCheckout,
        UpdateBlocksAccordingToConfigurationByJsLayout $updateBlocksAccordingToConfigurationByJsLayout
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
        $this->shippingConfig = $shippingConfig;
        $this->storeResolver = $storeResolver;
        $this->options = $options;
        $this->mergeComponentFromMagentoCheckout = $mergeComponentFromMagentoCheckout;
        $this->updateBlocksAccordingToConfigurationByJsLayout = $updateBlocksAccordingToConfigurationByJsLayout;
    }

    /**
     * @return array
     */
    private function getAddressAttributes()
    {
        /** @var \Magento\Eav\Api\Data\AttributeInterface[] $attributes */
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                continue;
            }
            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }

        return $elements;
    }

    /**
     * Convert elements(like prefix and suffix) from inputs to selects when necessary
     *
     * @param array $elements address attributes
     * @param array $attributesToConvert fields and their callbacks
     *
     * @return array
     */
    private function convertElementsToSelect($elements, $attributesToConvert)
    {
        $codes = array_keys($attributesToConvert);
        foreach (array_keys($elements) as $code) {
            if (!in_array($code, $codes)) {
                continue;
            }
            $options = call_user_func($attributesToConvert[$code]);
            if (!is_array($options)) {
                continue;
            }
            $elements[$code]['dataType'] = 'select';
            $elements[$code]['formElement'] = 'select';

            foreach ($options as $key => $value) {
                $elements[$code]['options'][] = [
                    'value' => $key,
                    'label' => $value,
                ];
            }
        }

        return $elements;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        $attributesToConvert = [
            'prefix' => [$this->options, 'getNamePrefixOptions'],
            'suffix' => [$this->options, 'getNameSuffixOptions'],
        ];

        $elements = $this->getAddressAttributes();
        $elements = $this->convertElementsToSelect($elements, $attributesToConvert);

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']['children']
            ['step-config']['children']['shipping-rates-validation']['children']
        )) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']['children']
            ['step-config']['children']['shipping-rates-validation']['children'] =
                $this->processShippingChildrenComponents(
                    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']['children']
                    ['step-config']['children']['shipping-rates-validation']['children']
                );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-address-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['billing-address-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-address-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'billingAddress',
                $fields
            );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-address-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'shippingAddress',
                $fields
            );
        }

        $jsLayout = $this->mergeComponentFromMagentoCheckout->execute($jsLayout);
        $jsLayout = $this->updateBlocksAccordingToConfigurationByJsLayout->execute($jsLayout);

        return $jsLayout;
    }

    /**
     * Process shipping configuration to exclude inactive carriers.
     *
     * @param array $shippingRatesLayout
     *
     * @return array
     */
    private function processShippingChildrenComponents($shippingRatesLayout)
    {
        $activeCarriers = $this->shippingConfig->getActiveCarriers(
            $this->storeResolver->getCurrentStoreId()
        );
        foreach (array_keys($shippingRatesLayout) as $carrierName) {
            $carrierKey = str_replace('-rates-validation', '', $carrierName);
            if (!array_key_exists($carrierKey, $activeCarriers)) {
                unset($shippingRatesLayout[$carrierName]);
            }
        }

        return $shippingRatesLayout;
    }
}
