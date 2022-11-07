<?php

namespace Dennis\GoogleFeedModule\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\UrlInterface;

class GenerateRSS
{
    protected $productCollectionFactory;
    private $urlInterface;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        UrlInterface $urlInterface
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->urlInterface = $urlInterface;
    }

    public function execute()
    {
        try {
            $om = ObjectManager::getInstance();
            $filesystem = $om->get('Magento\Framework\Filesystem');
            $directoryList = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
            $media = $filesystem->getDirectoryWrite($directoryList::APP);

            // Read the RSS template
            $rss_template = $media->readFile("code/Dennis/GoogleFeedModule/rss_template/rss.xml");
            $productCollection = $this->getProductCollections();
            $base_url = "http://localhost/";

            foreach ($productCollection as $product) {
                $product = $product->getData();

                $availability = $product['status'] ? 'in stock' : 'out of stock';

                // Update and replace contents in RSS template
                $rss_template = str_replace('{title}', $product['meta_title'], $rss_template);
                $rss_template = str_replace('{link}', $base_url . $product['url_key'] . '.html', $rss_template);
                $rss_template = str_replace('{description}', $product['meta_description'], $rss_template);
                $rss_template = str_replace('{id}', $product['entity_id'], $rss_template);
                $rss_template = str_replace('{image}', $base_url . 'media/catalog/product' . $product['image'], $rss_template);
                $rss_template = str_replace('{availability}', $availability, $rss_template);
                $rss_template = str_replace('{price}', $product['price'], $rss_template);

                // Write to File
                $filename = $product['url_key'] . "_rss.xml";
                $media->writeFile("code/Dennis/GoogleFeedModule/rss/" . $filename, $rss_template);
            }
        } catch (FileSystemException $e) {
        }
    }

    // Get All Products
    public function getProductCollections()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        return $collection;
    }

    public function getCurrentUrl()
    {
        return $this->urlInterface->getCurrentUrl();
    }
}
