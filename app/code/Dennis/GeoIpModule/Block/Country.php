<?php

namespace Dennis\GeoIpModule\Block;

use Dennis\GeoIpModule\Model\ResourceModel\Item\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;

class Country extends Template
{
    private $collection;
    private $ipAddress;

    public function __construct(
        Template\Context $context,
        Collection $collection,
        array $data = []
    ) {
        $this->collection = $collection;
        parent::__construct($context, $data);
    }

    /**
     * Determine user IP Address from different sources
     * @return string|null
     */
    public function getIpAddress()
    {
        foreach ([
                    'HTTP_CLIENT_IP',
                    'HTTP_X_FORWARDED_FOR',
                    'HTTP_X_FORWARDED',
                    'HTTP_X_CLUSTER_CLIENT_IP',
                    'HTTP_FORWARDED_FOR',
                    'HTTP_FORWARDED',
                    'REMOTE_ADDR'
                ] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip_address) {
                    return trim($ip_address);
                }
            }
        }
        return null;
    }

    /**
     * The User GeoIP
     * @return DataObject
     */
    public function getGeoIp(): DataObject
    {
        $this->ipAddress = $this->getIpAddress();
        return $this->collection->addFieldToFilter('ip', ['eq' => $this->ipAddress])->getFirstItem();
    }

    /**
     * Displays the country Code in the header.links
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->getTemplate()) {
            return parent::_toHtml();
        }
        $country_code = $this->getGeoIp()->getCountryCode();
        if ($country_code === 'US') {
            return '<li>' . $country_code . '</li>';
        }
        return '<li>Global</li>';
    }
}
