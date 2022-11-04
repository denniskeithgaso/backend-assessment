<?php
namespace Dennis\GeoIpModule\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * This is my first time doing Magento.
         * I just created a sample database that contains mock IP and Location.
         */

        $setup->getConnection()->insert(
            $setup->getTable('geo_ip'),
            [
                'ip' => '127.0.0.1',
                'country' => 'Philippines',
                'country_code' => 'PH'
            ]
        );

        $setup->getConnection()->insert(
            $setup->getTable('geo_ip'),
            [
                'ip' => '192.168.0.1',
                'country' => 'United States',
                'country_code' => 'US'
            ]
        );

        $setup->getConnection()->insert(
            $setup->getTable('geo_ip'),
            [
                'ip' => '192.168.0.2',
                'country' => 'Russia',
                'country_code' => 'RU'
            ]
        );

        $setup->getConnection()->insert(
            $setup->getTable('geo_ip'),
            [
                'ip' => '192.168.0.3',
                'country' => 'China',
                'country_code' => 'CH'
            ]
        );



        $setup->endSetup();
    }
}
