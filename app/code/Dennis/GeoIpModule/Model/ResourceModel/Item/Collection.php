<?php

namespace Dennis\GeoIpModule\Model\ResourceModel\Item;

use Dennis\GeoIpModule\Model\GeoIp;
use Dennis\GeoIpModule\Model\ResourceModel\GeoIpResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(GeoIp::class, GeoIpResource::class);
    }
}
