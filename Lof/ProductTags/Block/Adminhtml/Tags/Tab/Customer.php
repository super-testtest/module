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

class Customer extends \Magento\Backend\Block\Widget\Grid\Extended
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
        array $data = [],
        Visibility $visibility = null,
        Status $status = null
    ) {
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
        $this->setId('lof_producttags_tag_customers');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
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
    // protected function _addColumnFilterToCollection($column)
    // {
    //     // Set custom filter for in category flag
    //     if ($column->getId() == 'in_tag') {
    //         $productIds = $this->_getSelectedProducts();
    //         if (empty($productIds)) {
    //             $productIds = 0;
    //         }
    //         if ($column->getFilter()->getValue()) {
    //             $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
    //         } elseif (!empty($productIds)) {
    //             $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
    //         }
    //     } else {
    //         parent::_addColumnFilterToCollection($column);
    //     }
    //     return $this;
    // }

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
        $collection->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['id','customer_id'])
            ->group('customer_id');
        $collection = $collection->getData();
        $customer_ids = array_column($collection, 'customer_id');
        $collection = $this->_customerFactory->create();
        if(empty($customer_ids)){
            $customer_ids =  0 ;
        }
        $collection->addFieldToFilter("entity_id",array("in",$customer_ids));
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
            'firstname',
            [
                'header' => __('First Name'),
                'index' => 'firstname',
                'class' => 'xxx',
                'width' => '50px'
            ]
        );
        $this->addColumn(
            'middlename',
            [
                'header' => __('Middle Name'),
                'index' => 'middlename',
                'class' => 'xxx',
                'width' => '50px'
            ]
        );
        $this->addColumn(
            'lastname',
            [
                'header' => __('Last Name'),
                'index' => 'lastname',
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
                            'base' => 'customer/index/edit'
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
        return $this->getUrl('lof_producttags/*/customersubmitgrid', ['_current' => true]);
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
