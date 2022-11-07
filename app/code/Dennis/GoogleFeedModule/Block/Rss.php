<?php
namespace Dennis\GoogleFeedModule\Block;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\View\Element\Template;
use Psr\Log\LoggerInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

class Rss extends Template
{
    protected $productCollectionFactory;
    protected $directoryList;
    protected $driverFile;
    protected $logger;

    public function __construct(
        Template\Context $context,
        CollectionFactory $productCollectionFactory,
        DirectoryList $directoryList,
        File $driverFile,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->directoryList =$directoryList;
        $this->driverFile = $driverFile;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * Read RSS Feed and return them
     * @return string
     */
    public function getAllRssFeed()
    {
        $files = $this->getAllFiles();
        $rss = "";
        foreach ($files as $file) {
            try {
                $rss .= htmlspecialchars($this->driverFile->fileGetContents($file)) . "<br><hr>";
            } catch (FileSystemException $e) {
                $this->logger->error($e->getMessage());
            }
        }
        return $rss;
    }

    /**
     * Get all RSS XML File Path
     * @param $path
     * @return array|string[]
     */
    public function getAllFiles($path = '/code/Dennis/GeoIpModule/rss/')
    {
        $paths = [];
        try {
            $path = $this->directoryList->getPath($this->directoryList::APP) . $path;
            $paths =  $this->driverFile->readDirectory($path);
        } catch (FileSystemException $e) {
            $this->logger->error($e->getMessage());
        }

        return $paths;
    }
}
