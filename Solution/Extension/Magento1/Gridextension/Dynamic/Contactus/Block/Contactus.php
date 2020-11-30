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
 
class Dynamic_Contactus_Block_Contactus extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $storeId = Mage::app()->getStore(true)->getId();
        $collection = Mage::getModel("dynamic_contactus/contactus")->getCollection();
        $collection->addFieldToFilter('contactus_status','1');
        $collection->addFieldToFilter('store_id',$storeId );
        if ($this->getSidebar()){
            $collection->addFieldToFilter('contactus_sidebar', '1');
        }

        $collection->setOrder('contactus_position', 'ASC')
               ->load();

        $this->setContactuss($collection);
    }
}