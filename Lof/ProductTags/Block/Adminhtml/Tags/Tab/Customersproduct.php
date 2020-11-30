<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product in category grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Lof\ProductTags\Block\Adminhtml\Tags\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\ObjectManager;

class Customersproduct extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var Visibility
     */
    private $visibility;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     * @param Visibility|null $visibility
     * @param Status|null $status
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Lof\ProductTags\Model\ResourceModel\Relatedtag\CollectionFactory $relatedfactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, 
        array $data = [],
        Visibility $visibility = null,
        Status $status = null
        ) { 
        $this->_productCollectionFactory = $productCollectionFactory;  
        $this->_customerFactory = $customerFactory;  
        $this->relatedfactory = $relatedfactory;
        $this->_productFactory = $productFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->visibility = $visibility ?: ObjectManager::getInstance()->get(Visibility::class);
        $this->status = $status ?: ObjectManager::getInstance()->get(Status::class);
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('lof_producttags_tag_customersproduct');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);
    }

    /**
     * @return array|null
     */
    public function getTag()
    {
        return $this->_coreRegistry->registry('tag');
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {  
        //Set custom filter for in category flag
        if ($column->getId() == 'in_tag') {
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
     * Checks when this block is readonly
     *
     * @return bool
     */
    public function isReadonly()
    {
        return $this->getTag() && $this->getTag()->getRelatedReadonly();
    }


    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->relatedfactory->create();
        $collection->addFieldToFilter('relatedtag',$this->getTag()->getTagTitle());
        $collection->addFieldToFilter('customer_id', ['neq' => 'NULL']);
        //$collection->addFieldToFilter('is_approve',"unapprove");
        $collection->getSelect()
        ->reset(\Zend_Db_Select::COLUMNS)
        ->columns(['id','product_id_related','customer_id'])
        ->group('product_id_related');
        $collection = $collection->getData();
        $product_id_related = array_column($collection, 'product_id_related');
        $collection = $this->_productCollectionFactory->create();
        if(empty($product_id_related)){
            $product_id_related =  0 ;
        }

        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('entity_id',array("in",$product_id_related));   

        $this->setCollection($collection);
        return parent::_prepareCollection();


        
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
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

    /**
     * @return string
     */
    public function getGridUrl()
    {    
        return $this->getUrl('lof_producttags/*/customerproductgrid', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {   
        $products = $this->getRequest()->getPost('selected_products');

        if ($products === null && $this->getTag()) {
            $products = $this->getTag()->getProductsPosition();
            return array_keys($products);
        }
        return $products;
    }
}
