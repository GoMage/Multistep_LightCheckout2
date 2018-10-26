<?php

namespace GoMage\SuperLightCheckout\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.1.1', '<')) {
            $this->updateTo111($setup);
        }

        $setup->endSetup();
    }

    private function updateTo111(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->createTable(
            $setup->getConnection()
                ->newTable($setup->getTable(\GoMage\SuperLightCheckout\Model\ResourceModel\SocialCustomer::TABLE_NAME))
                ->addColumn(
                    'social_customer_id',
                    Table::TYPE_INTEGER,
                    11,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'Social Customer ID'
                )->addColumn(
                    'social_id',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false],
                    'Social Id'
                )->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    10,
                    ['unsigned' => true, 'nullable' => false],
                    'Customer Id'
                )->addColumn('type', Table::TYPE_TEXT, 255, ['default' => ''], 'Type')
                ->addForeignKey(
                    $setup->getFkName('gomage_social_customer', 'customer_id', 'customer_entity', 'entity_id'),
                    'customer_id',
                    $setup->getTable('customer_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Social Customer Table')
        );
    }
}
