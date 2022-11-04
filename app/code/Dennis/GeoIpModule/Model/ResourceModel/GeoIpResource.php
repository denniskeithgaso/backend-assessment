<?php

namespace Dennis\GeoIpModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GeoIpResource extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('geo_ip', 'id');
    }
}
