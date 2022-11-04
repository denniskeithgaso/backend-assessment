<?php

namespace Dennis\GeoIpModule\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * This is my first time doing Magento.
         * I just created a sample database that contains mock IP and Location.
         */

        try {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('geo_ip')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'GeoIP ID'
            )->addColumn(
                'ip',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'IP Address'
            )->addColumn(
                'country',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Country of the IP address'
            )->addColumn(
                'country_code',
                Table::TYPE_TEXT,
                2,
                ['nullable' => false],
                '2 letter country code'
            )->addIndex(
                $setup->getIdxName('geo_ip', ['ip']),
                ['ip']
            )->setComment(
                'Sample Items'
            );
            $setup->getConnection()->createTable($table);
        } catch (\Zend_Db_Exception $exception) {
            print("Install Schema error due to: " . $exception->getMessage());
        }

        $setup->endSetup();
    }
}
