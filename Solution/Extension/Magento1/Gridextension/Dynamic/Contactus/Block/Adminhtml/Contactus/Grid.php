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
 * @package    Dynamic_Contactus
 * @author     DynamicSoft Team
 * @copyright  Copyright (c) 2010-2012 Dynamic Co. (http://mage-addons.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Dynamic_Contactus_Block_Adminhtml_Contactus_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('contactusGrid');
        $this->setDefaultSort('contactus_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Dynamic_Contactus_Block_Adminhtml_Contactus_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('dynamic_contactus/contactus')->getCollection());
        return parent::_prepareCollection();
    }

    /**
     * Preparing colums for grid
     *
     * @return Dynamic_Contactus_Block_Adminhtml_Contactus_Grid
     */
    protected function _prepareColumns()
    {
		$this->addColumn('contactus_id', array(
            'header' => Mage::helper('dynamic_contactus')->__('ID'),
            'index' => 'contactus_id',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('dynamic_contactus')->__('Name'),
            'index'     => 'name',
        ));
        $this->addColumn('telephone', array(
            'header'    => Mage::helper('dynamic_contactus')->__('Telephone'),
            'index'     => 'telephone',
        ));
        $this->addColumn('email', array(
            'header'    => Mage::helper('dynamic_contactus')->__('Email'),
            'index'     => 'email',
        ));
        $this->addColumn('referralcode', array(
            'header'    => Mage::helper('dynamic_contactus')->__('Referral Code'),
            'index'     => 'referralcode',
        ));
        $this->addColumn('know', array(
            'header'    => Mage::helper('dynamic_contactus')->__('How did you hear about us?'),
            'index'     => 'know',
        ));
        $this->addColumn('comment', array(
            'header'    => Mage::helper('dynamic_contactus')->__('Inquiry'),
            'index'     => 'comment',
        ));
          $this->addColumn('no', array(
            'header'    => Mage::helper('dynamic_contactus')->__('Anti-Spam* What does 3 plus 5 equal?'),
            'index'     => 'no',
        ));


        $this->addColumn("created_time", array(
            "header" => Mage::helper("dynamic_contactus")->__("Date"),
            "type" =>   "datetime", 
            "index" =>  "created_time",
        ));
		$this->addColumn('action',
				array(
					'header' => Mage::helper('dynamic_contactus')->__('Action'),
					'width' => '80',
					'type' => 'action',
					'getter' => 'getId',
					'actions' => array(
						array(
							'caption' => Mage::helper('dynamic_contactus')->__('View'),
							'url' => array('base' => '*/*/edit'),
							'field' => 'id'
						)
					),
					'filter' => false,
					'sortable' => false,
					'index' => 'stores',
					'is_system' => true,
		));
		
		
        return parent::_prepareColumns();
    }
	
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
