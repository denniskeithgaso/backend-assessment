<?php

namespace Dennis\GeoIpModule\Model;

use Magento\Framework\Model\AbstractModel;

class GeoIp extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Dennis\GeoIpModule\Model\ResourceModel\GeoIpResource::class);
    }
}
