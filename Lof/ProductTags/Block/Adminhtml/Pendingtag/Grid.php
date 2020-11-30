<?php
namespace Lof\ProductTags\Block\Adminhtml\Pendingtag;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Lof\ProductTags\Model\RelatedtagFactory $RelatedtagFactory
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Lof\ProductTags\Model\ResourceModel\Tag\Grid\CollectionFactory $relatedfactory,
        \Lof\ProductTags\Model\RelatedtagFactory $RelatedtagFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_relatedtagFactory = $RelatedtagFactory;
        $this->relatedfactory = $relatedfactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        //$this->setId('postGrid');
        //$this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        //$this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->relatedfactory->create();
        $collection->addFieldToFilter("status","0");
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'tag_title',
            [
                'header' => __('Tag'),
                'type' => 'text',
                'index' => 'tag_title',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'number_products',
            [
                'header' => __('Products'),
                'type' => 'text',
                'index' => 'number_products',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                 //'renderer'  => '\Lof\ProductTags\Block\Adminhtml\Renderer\Products'
            ]
        );
        $this->addColumn(
            'customer_id',
            [
                'header' => __('Customers'),
                'type' => 'text',
                //'index' => 'customer_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'renderer'  => '\Lof\ProductTags\Block\Adminhtml\Renderer\Customers'
            ]
        );
        $this->addColumn(
            'edit',
            [
                'header' => __('View'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('View'),
                        'url' => [
                            'base' => '*/*/pendingtagedit'
                        ],
                        'field' => 'tag_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
        

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
		

		

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }
     protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tag_id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('lof_producttags/*/pendingmassdelete'),
                'confirm' => __('Are you sure want to delete selected Pending Tag(s)?')
            )
        );
        
        $this->getMassactionBlock()->addItem(
            'approve',
            array(
                'label' => __('Approve'),
                'url' => $this->getUrl('lof_producttags/*/pendingmassenable'),
                'confirm' => __('Are you sure want to approve selected Pending Tag(s)?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'pending',
            array(
                'label' => __('Pending'),
                'url' => $this->getUrl('lof_producttags/*/pendingmasspending'),
                'confirm' => __('Are you sure want to pending selected Pending Tag(s)?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'disable',
            array(
                'label' => __('Disable'),
                'url' => $this->getUrl('lof_producttags/*/pendingmassdisable'),
                'confirm' => __('Are you sure want to disable selected Pending Tag(s)?')
            )
        );
        
        return $this;
    }
	

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('lof_producttags/*/pendingtag', ['_current' => true]);
    }

    public function getRowUrl($row)
    {
		
         return "";
		
    }

	

}