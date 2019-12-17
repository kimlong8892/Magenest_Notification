<?php

namespace Magenest\Notification\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.5') < 0) {
            $installer = $setup;
            $installer->startSetup();
            $connection = $installer->getConnection();
            $table = $installer->getConnection()->newTable(
                $installer->getTable('promo_notification')
            )->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => false,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'entity_id'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'name'
            )->addColumn(
                'status',
                Table::TYPE_BOOLEAN,
                255,
                [
                    'nullable' => false
                ],
                'name'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                ],
                'Created at'
            )->addColumn(
                'short_description',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'short_description'
            )->addColumn(
                'redirect_url',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'redirect_url'
            );
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
        }
    }
}
