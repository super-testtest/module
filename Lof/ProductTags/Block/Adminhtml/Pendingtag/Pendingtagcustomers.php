<?php

namespace Lof\ProductTags\Block\Adminhtml\Pendingtag;

use Dynamic\CardSlider\Model\ShowimagesFactory;

class Pendingtagcustomers extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
     * @param ShowimagesFactory $showimagesFactory
     * @param \Dynamic\CardSlider\Model\ResourceModel\Showslider\CollectionFactory $imagesCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Lof\ProductTags\Model\ResourceModel\Relatedtag\CollectionFactory $relatedfactory,
       \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        array $data = []
    ) {
        $this->_customerFactory = $customerFactory;  
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
        $this->setId('customerstagGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
       
    }

    

    /**
     * prepare collection
     */
    public function _prepareCollection()
    {
        $data = explode('/',$_SERVER['HTTP_REFERER']);
        $key = array_search("tag_id",$data)+1;

        $tag_id = $data[$key];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->get('Lof\ProductTags\Model\Tag')->load($tag_id);
        $collection = $this->relatedfactory->create();
        $collection->addFieldToFilter('relatedtag',$model->getTagTitle());
        $collection->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['id','customer_id'])
            ->group('customer_id');
        $collection = $collection->getData();
        $customer_ids = array_column($collection, 'customer_id');
        $collection = $this->_customerFactory->create();
        $collection->addFieldToFilter("entity_id",array("in",$customer_ids));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    public function _prepareColumns()
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
        return $this->getUrl('lof_producttags/*/pendingtagcustomers', ['_current' => true]);
    }
    
    public function getTabUrl()
    {
       
        return $this->getUrl('lof_producttags/*/pendingtagcustomers');
    }

    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        
        return true;
    }
  
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return true;
    }
    
    public function isAjaxLoaded()
    {
        return true;
    }
    
    public function getTabLabel()
    {
        return __('Products Tagged by Administrators');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Products Tagged by Administrators');
    }
}
