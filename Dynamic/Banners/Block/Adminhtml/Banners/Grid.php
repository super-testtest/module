<?php
namespace Dynamic\Banners\Block\Adminhtml\Banners;

/**
 * Adminhtml Banners grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Dynamic\Banners\Model\ResourceModel\Banners\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Dynamic\Banners\Model\Banners
     */
    protected $_banners;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Dynamic\Banners\Model\Banners $bannersPage
     * @param \Dynamic\Banners\Model\ResourceModel\Banners\CollectionFactory $collectionFactory
     * @param \Magento\Core\Model\PageLayout\Config\Builder $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Dynamic\Banners\Model\Banners $banners,
        \Dynamic\Banners\Model\ResourceModel\Banners\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_banners = $banners;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('bannersGrid');
        $this->setDefaultSort('banners_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \Dynamic\Banners\Model\ResourceModel\Banners\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn('banners_id', [
            'header'    => __('ID'),
            'index'     => 'banners_id',
        ]);
       
        $this->addColumn('title', ['header' => __('Title'), 'index' => 'title']);

        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'index' => 'image',
                'class' => 'image',
                'renderer' => 'Dynamic\Banners\Block\Adminhtml\Banners\Grid\Renderer\Image',
                'filter' => false
            ]
        );

        $arrBannerStatus = array('1' => __('Enable'), '0' => __('Disable'));
        $this->addColumn('status', ['header' => __('Status'), 'type' => 'options', 'options' => $arrBannerStatus, 'index' => 'status']);
        
        $this->addColumn('sort_order', ['header' => __('Sort Order'), 'index' => 'sort_order']);

        $this->addColumn(
            'action',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit',
                            'params' => ['store' => $this->getRequest()->getParam('store')]
                        ],
                        'field' => 'banners_id'
                    ]
                ],
                'sortable' => false,
                'filter' => false,
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }

   protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banners_id');
        $this->getMassactionBlock()->setFormFieldName('banners_id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('banners/*/massDelete'),
                'confirm' => __('Are you sure want to delete selected banner(s)?')
            )
        );
        
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change Status'),
                'url' => $this->getUrl('banners/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => [['label' => '', 'value' => ''], ['label' => 'Enable', 'value' => 1], ['label' => 'Disable', 'value' => 0]]
                    ]
                ]
            ]
        );
        
        return $this;
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['banners_id' => $row->getId()]);
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
}
