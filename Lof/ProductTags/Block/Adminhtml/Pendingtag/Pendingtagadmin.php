<?php

namespace Lof\ProductTags\Block\Adminhtml\Pendingtag;

class Pendingtagadmin extends \Magento\Backend\Block\Widget\Grid\Extended
{
   
  

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Products constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Lof\ProductTags\Model\ResourceModel\Tag\CollectionFactory $relatedfactory
     * @param  \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Lof\ProductTags\Model\ResourceModel\Tag\CollectionFactory $relatedfactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        // $this->_productCollectionFactory = $productCollectionFactory;  
        $this->relatedfactory = $relatedfactory;
        $this->registry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('admintagGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setDefaultFilter(['in_admin' => 1]);   
    }
    public function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_admin') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
         return $this;
    }
    

    /**
     * prepare collection
     */
    public function _prepareCollection()
    {
        $data = explode('/',$_SERVER['HTTP_REFERER']);
        $key = array_search("tag_id",$data)+1;

        $tag_id = $data[$key];
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'visibility'
        )->addAttributeToSelect(
            'status'
        )->addAttributeToSelect(
            'price'
        )->joinField(
            'position',
            'lof_producttags_product',
            'position',
            'product_id=entity_id',
            'tag_id=' . $tag_id,
            'left'
        );
        $productIds = $this->_getSelectedProducts();
        if (empty($productIds)) {
            $productIds = 0;
            
        }
        
        $collection->addFieldToFilter('entity_id', ['in' => $productIds]);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    public function _prepareColumns()
    {
        $this->addColumn(
            'in_admin',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'field_name' => 'in_admin[]',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->_getSelectedProducts()
            ]
        );
        
        $this->addColumn(
            'entity_id',
            [
                'header' => __('Id#'),
                'index' => 'entity_id',
                'class' => 'xxx',
                'width' => '50px'
               
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px'
             
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Product Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px'
              
            ]
        );
        $this->addColumn(
            'edit',
            [
                'header' => __('View'),
                'type' => 'action',
                'getter' => 'getEntityId',
                'actions' => [
                    [
                        'caption' => __('View'),
                        'url' => [
                            'base' => 'catalog/product/edit'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }
    public function _getSelectedProducts(){
        $data = explode('/',$_SERVER['HTTP_REFERER']);
        $key = array_search("tag_id",$data)+1;

        $tag_id = $data[$key];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create(\Lof\ProductTags\Model\Tag::class)->load($tag_id);
        $customer_collection = $objectManager->create('\Lof\ProductTags\Model\ResourceModel\Relatedtag\Collection');
        $customer_product_ids = array();
        if($model->getTagId()){
            $customer_collection->addFieldToFilter('relatedtag',$model->getTagTitle());
            $customer_collection->addFieldToFilter('customer_id', ['eq' => 'NULL']);
            $customer_collection->getSelect()
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['product_id_related']);
            $customer_collection = $customer_collection->getData();
            $customer_product_ids = array_column($customer_collection, 'product_id_related');
        }
        
        $tag_ids = array();
        if($tag_id!=""):
            $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
            $connection= $this->_resources->getConnection();
            $themeTable = $this->_resources->getTableName('lof_producttags_product');
            $sql = "select * from " . $themeTable . " where tag_id=".$tag_id;
            $result = $connection->fetchAll($sql);
            $tag_ids = array_column($result, 'product_id');
        endif;
        if(sizeof($customer_product_ids) > 0 && sizeof($tag_ids) > 0){
            $tag_ids = array_diff($tag_ids,$customer_product_ids);     
        }
       

        return $tag_ids;
    }
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('lof_producttags/*/pendingtagadmin', ['_current' => true]);
    }
    
    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    
  
    
    public function isAjaxLoaded()
    {
        return true;
    }
    
}
