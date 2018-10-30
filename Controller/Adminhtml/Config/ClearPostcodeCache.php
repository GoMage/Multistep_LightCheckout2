<?php

namespace GoMage\SuperLightCheckout\Controller\Adminhtml\Config;

use GoMage\SuperLightCheckout\Model\PostCode\EmptyCollection;
use GoMage\SuperLightCheckout\Setup\InstallData;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;

class ClearPostcodeCache extends Action
{
    /**
     * @type JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var EmptyCollection
     */
    private $emptyCollection;

    /**
     * @param Action\Context $context
     * @param JsonFactory $resultJsonFactory
     * @param EmptyCollection $emptyCollection
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        EmptyCollection $emptyCollection
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->emptyCollection = $emptyCollection;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $success = true;
        $message = __('Cache was successfully cleared');

        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        try {
            $this->emptyCollection->execute();
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }


        return $result->setData(['success' => $success, 'message' => $message]);
    }
}
