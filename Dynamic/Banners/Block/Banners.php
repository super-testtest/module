<?php

namespace Dynamic\Banners\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Dynamic\Banners\Model\ResourceModel\Banner\Collection;
use \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use \Magento\Framework\Filesystem;
use \Magento\Framework\Image\AdapterFactory;
use \Magento\Catalog\Model\CategoryFactory;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;

/**
 * Banners content block
 */
class Banners extends \Magento\Framework\View\Element\Template
{
    /**
     * Banners collection
     *
     * @var Dynamic\Banners\Model\ResourceModel\Banners\Collection
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $_categoryFactory;

    protected $categoryRepository;

    protected $_filesystem;

    protected $_imageFactory;

    protected $_categoryModelFactory;

    protected $_productCollectionFactory;


    protected $_bannersCollection = null;
    
    /**
     * Banners factory
     *
     * @var \Dynamic\Banners\Model\BannersFactory
     */
    protected $_bannersCollectionFactory;
    
    /** @var \Dynamic\Banners\Helper\Data */
    protected $_dataHelper;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Dynamic\Banners\Model\ResourceModel\Banners\CollectionFactory $bannersCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Dynamic\Banners\Model\ResourceModel\Banners\CollectionFactory $bannersCollectionFactory,
        \Dynamic\Banners\Helper\Data $dataHelper,CollectionFactory $catcollection, Filesystem $filesystem,AdapterFactory $imageFactory, CategoryFactory $categoryModelFactory, ProductCollection $productCollectionFactory,
        array $data = []
    ) {
        $this->_bannersCollectionFactory = $bannersCollectionFactory;
        $this->_storeManager = $context->getStoreManager();     
        $this->_categoryFactory = $catcollection;
        $this->_filesystem = $filesystem;  
        $this->_imageFactory = $imageFactory;  
        $this->_categoryModelFactory = $categoryModelFactory; 
        $this->_productCollectionFactory = $productCollectionFactory; 

        $this->_dataHelper = $dataHelper;
        parent::__construct(
            $context,
            $data
        );
    }
    
    /**
     * Retrieve banners collection
     *
     * @return Dynamic\Banners\Model\ResourceModel\Banners\Collection
     */
    protected function _getCollection()
    {
        $collection = $this->_bannersCollectionFactory->create();
        return $collection;
    }
    
    /**
     * Retrieve prepared banners collection
     *
     * @return Dynamic\Banners\Model\ResourceModel\Banners\Collection
     */
    public function getCollection()
    {
        if (is_null($this->_bannersCollection)) {
            $this->_bannersCollection = $this->_getCollection();
            $this->_bannersCollection->setCurPage($this->getCurrentPage());
            $this->_bannersCollection->setPageSize($this->_dataHelper->getBannersPerPage());
            $this->_bannersCollection->setOrder('sort_order','asc');
            $this->_bannersCollection->addFieldToFilter('status',1);
        }

        return $this->_bannersCollection;
    }
    public function resize($image, $width = null, $height = null,$aspectratio = null)
    {

        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('catalog/category/').$image;
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/'.$width.'/').$image;   
        //create image factory...
        $imageResize = $this->_imageFactory->create();         
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(true);         
        $imageResize->keepTransparency(true);
        $imageResize->keepFrame(true);         
        $imageResize->keepAspectRatio($aspectratio);  
        $imageResize->backgroundColor(array(255,255,255));       
        $imageResize->resize($width,$height);  
        //destination folder                
        $destination = $imageResized ;    
        //save image      
        $imageResize->save($destination); 
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$width.'/'.$image;
        return $resizedURL;

    }
    /**
     * Fetch the current page for the banners list
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }
    
    /**
     * Return URL to item's view page
     *
     * @param Dynamic\Banners\Model\Banners $bannersItem
     * @return string
     */
    public function getItemUrl($bannersItem)
    {
        return $this->getUrl('*/*/view', array('id' => $bannersItem->getId()));
    }
    
    /**
     * Return URL for resized Banners Item image
     *
     * @param Dynamic\Banners\Model\Banners $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width)
    {
        return $this->_dataHelper->resize($item, $width);
    }
    public function getBannerMediaUrl()
    {
        $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager()
    {
        $pager = $this->getChildBlock('banners_list_pager');
        if ($pager instanceof \Magento\Framework\Object) {
            $bannersPerPage = $this->_dataHelper->getBannersPerPage();

            $pager->setAvailableLimit([$bannersPerPage => $bannersPerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(TRUE);
            $pager->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );

            return $pager->toHtml();
        }

        return NULL;
    }
    
    public function getSliderCollection()
    {
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $categoryIds = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface')->getValue('custom_config/slider_config/categories_used_in_selection');
        $limit = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface')->getValue('custom_config/slider_config/slider_limit');
        $categoryFilters = [];

        foreach (explode(',', $categoryIds) as $categoryId) {
            $categoryFilters[]['finset'] = $categoryId;
        }

        $collection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('slideshow')
            ->addAttributeToSelect('slideshow_label')
            ->addFieldToFilter('visibility', [
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG
            ])
            ->addFieldToFilter('slideshow', ['neq' => 'NULL'])
            ->joinField(
                'category_id',
                'catalog_category_product',
                'category_id',
                'product_id = entity_id',
                ['category_id' => $categoryFilters],
                'left'
            );

        $collection->getSelect()
            ->where('category_id', $categoryFilters)
            ->group('e.entity_id')
            ->order(new \Zend_Db_Expr('RAND()'))
            ->limit($limit);


        return $collection;
    }
}
