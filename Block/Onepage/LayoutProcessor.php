<?php

namespace GoMage\SuperLightCheckout\Block\Onepage;

use Magento\Checkout\Block\Checkout\AttributeMerger;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Helper\Data;
use Magento\Framework\App\ObjectManager;
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
    protected $attributeMapper;

    /**
     * @var AttributeMerger
     */
    protected $merger;

    /**
     * @var \Magento\Customer\Model\Options
     */
    private $options;

    /**
     * @var Data
     */
    private $checkoutDataHelper;

    /**
     * @var StoreResolverInterface
     */
    private $storeResolver;

    /**
     * @var \Magento\Shipping\Model\Config
     */
    private $shippingConfig;

    /**
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     * @param AttributeMerger $merger
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        AttributeMerger $merger
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
    }

    /**
     * @deprecated
     * @return \Magento\Customer\Model\Options
     */
    private function getOptions()
    {
        if (!is_object($this->options)) {
            $this->options = ObjectManager::getInstance()->get(\Magento\Customer\Model\Options::class);
        }

        return $this->options;
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
            'prefix' => [$this->getOptions(), 'getNamePrefixOptions'],
            'suffix' => [$this->getOptions(), 'getNameSuffixOptions'],
        ];

        $elements = $this->getAddressAttributes();
        $elements = $this->convertElementsToSelect($elements, $attributesToConvert);

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['step-config']['children']['shipping-rates-validation']['children']
        )) {
            //todo
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['step-config']['children']['shipping-rates-validation']['children'] =
                $this->processShippingChildrenComponents(
                    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                    ['step-config']['children']['shipping-rates-validation']['children']
                );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['billingAddress']['children']['billing-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'billingAddress',
                $fields
            );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'shippingAddress',
                $fields
            );
        }

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
        $activeCarriers = $this->getShippingConfig()->getActiveCarriers(
            $this->getStoreResolver()->getCurrentStoreId()
        );
        foreach (array_keys($shippingRatesLayout) as $carrierName) {
            $carrierKey = str_replace('-rates-validation', '', $carrierName);
            if (!array_key_exists($carrierKey, $activeCarriers)) {
                unset($shippingRatesLayout[$carrierName]);
            }
        }

        return $shippingRatesLayout;
    }

    /**
     * Get active carriers list.
     *
     * @return array
     * @deprecated
     */
    private function getShippingConfig()
    {
        if (!$this->shippingConfig) {
            $this->shippingConfig = ObjectManager::getInstance()->get(\Magento\Shipping\Model\Config::class);
        }

        return $this->shippingConfig;
    }

    /**
     * Get store resolver.
     *
     * @return StoreResolverInterface
     * @deprecated
     */
    private function getStoreResolver()
    {
        if (!$this->storeResolver) {
            $this->storeResolver = ObjectManager::getInstance()->get(StoreResolverInterface::class);
        }

        return $this->storeResolver;
    }
}
