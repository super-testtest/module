<?php
/**
 * Dynamicsoft Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0).
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to obtain it through the world-wide-web, please send
 * an email to support@mage-addons.com so we can send you a copy immediately.
 *
 * @category   Dynamic
 * @package    Dynamic_Brand
 * @author     DynamicSoft Team
 * @copyright  Copyright (c) 2010-2012 Dynamic Co. (http://mage-addons.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Dynamic_Brand_Block_Adminhtml_Brand_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    const REPEATER = '_';
    const PREFIX_END = '';
    protected $_options = array();
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandGrid');
        $this->setDefaultSort('brand_position');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Dynamic_Brand_Block_Adminhtml_Brand_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('dynamic_brand/brand')->getCollection());
        return parent::_prepareCollection();
    }

    /**
     * Preparing colums for grid
     *
     * @return Dynamic_Brand_Block_Adminhtml_Brand_Grid
     */
    protected function _prepareColumns()
    {
		$this->addColumn('brand_id', array(
			'header' => Mage::helper('dynamic_brand')->__('ID'),
			'index' => 'brand_id',
		));
	   //echo "<pre>";print_r($this->getOptionArray());die;
        /*$this->addColumn('brand_position', array(
            'header'    => Mage::helper('dynamic_brand')->__('Position'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'brand_position',
            'type'      => 'number',
        ));*/

        $this->addColumn('brand_name', array(
            'header'    => Mage::helper('dynamic_brand')->__('Brand Name'),
            'index'     => 'brand_name',
        ));
        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('dynamic_brand')->__('Sort Order'),
            'align'     => 'left',
            'index'     => 'sort_order',
        ));
        $this->addColumn('url', array(
            'header'    => Mage::helper('dynamic_brand')->__('URL'),
            'index'     => 'url',
        ));
        $this->addColumn("image", array(
        "header" => Mage::helper("dynamic_brand")->__("Image"),
        "index" => "image",
        "renderer" =>"Dynamic_Brand_Block_Adminhtml_Renderer_Image",
        ));

      
        /*$this->addColumn('brand_title', array(
            'header'    => Mage::helper('dynamic_brand')->__('Title'),
            'align'     => 'left',
            'index'     => 'brand_title',
        ));
		
    

        $this->addColumn('brand_text', array(
            'header'    => Mage::helper('dynamic_brand')->__('Text'),
            'align'     => 'left',
            'index'     => 'brand_text',
        ));
        $this->addColumn('store_id', array(
            'header'        => Mage::helper('dynamic_brand')->__('Store View'),
            'index'         => 'store_id',
            'type'          => 'store',
            'store_all'     => true,
            'store_view'    => true,
            'sortable'      => true,
            //'filter_condition_callback' => array($this, '_filterStoreCondition'),

        ));*/
        
		/*
        $this->addColumn('brand_status', array(
            'header'    => Mage::helper('dynamic_brand')->__('Status'),
            'align'     => 'center',
			'type' 		=> 'options',
            'index'     => 'brand_status',
			'options' => array(
				1 => 'Enabled',
				2 => 'Disabled',
			),
        ));*/

       /*  $fieldset->addField('category', 'multiselect',
        array(
            'label' => Mage::helper('dynamic_brand')->__('Category'),
            'class' => 'required-entry',
            'required' => true,
            'values' => $this->getOptionArray(),
            'name' => 'category',

        ));*/
        $this->addColumn('category', array(
        'header'    => Mage::helper('dynamic_brand')->__('Category'),
        'width'     => '180px',
        'index'     => 'category',
        "renderer" =>"Dynamic_Brand_Block_Adminhtml_Renderer_Category",

        ));
		$this->addColumn('action',
				array(
					'header' => Mage::helper('dynamic_brand')->__('Action'),
					'width' => '80',
					'type' => 'action',
					'getter' => 'getId',
					'actions' => array(
						array(
							'caption' => Mage::helper('dynamic_brand')->__('Edit'),
							'url' => array('base' => '*/*/edit'),
							'field' => 'id'
						)
					),
					'filter' => false,
					'sortable' => false,
					'index' => 'stores',
					'is_system' => true,
		));
		$this->addExportType('*/*/exportCsv', Mage::helper('dynamic_brand')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('dynamic_brand')->__('XML'));
		
        return parent::_prepareColumns();
    }
   /* protected function _filterStoreCondition($collection, $column){
           if(!$value = $column->getFilter()->getValue()) {
          return;
        }
        $this->getCollection()->addStoreFilter($value);
    }*/
	protected function _prepareMassaction() {
		$this->setMassactionIdField('brand_id');
		$this->getMassactionBlock()->setFormFieldName('dynamic_brand');
		$this->getMassactionBlock()->addItem('delete', array(
			'label' => Mage::helper('dynamic_brand')->__('Delete'),
			'url' => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('dynamic_brand')->__('Are you sure?')
		));
		$statuses = Mage::getSingleton('dynamic_brand/status')->getOptionArray();
			array_unshift($statuses, array('label' => '', 'value' => ''));
			$this->getMassactionBlock()->addItem('brand_status', array(
				'label' => Mage::helper('dynamic_brand')->__('Change status'),
				'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
				'additional' => array(
					'visibility' => array(
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('dynamic_brand')->__('Status'),
						'values' => $statuses
					)
				)
			));
		return $this;
	}
	
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
