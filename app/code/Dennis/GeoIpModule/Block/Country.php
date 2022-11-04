<?php

namespace Dennis\GeoIpModule\Block;

use Dennis\GeoIpModule\Model\ResourceModel\Item\Collection;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;

class Country extends Template
{
    private $collection;
    private $ipAddress;
    private $urlInterface;
    protected $redirect;
    protected $response;

    public function __construct(
        Template\Context $context,
        Collection $collection,
        UrlInterface $urlInterface,
        Http $response,
        RedirectInterface $redirect,
        array $data = []
    ) {
        $this->collection = $collection;
        $this->urlInterface = $urlInterface;
        $this->redirect = $redirect;
        $this->response = $response;
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
     * Get host url
     * @param $url
     * @return string
     */

    public function getCurrentUrl()
    {
        return $this->urlInterface->getCurrentUrl();
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
        if ($country_code === 'RU' || $country_code === 'CH') {
            $current_url = $this->getCurrentUrl();
            $error_page = $current_url . '404';

            // If url is already in error page, don't redirect anymore.
            if (str_contains($current_url, '404')) {
                return;
            }
            $this->redirect->redirect($this->response, $error_page);
            return;
        }
        if ($country_code === 'US') {
            return '<li>' . $country_code . '</li>';
        }
        return '<li>Global</li>';
    }
}
