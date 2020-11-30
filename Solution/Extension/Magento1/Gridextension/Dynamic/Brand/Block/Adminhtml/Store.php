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
  
class Dynamic_Brand_Block_Adminhtml_Store extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row) 
    {   
        $stores = $row->getStoreId();
        if($stores == 0){
            $store_details = Mage::app()->getStores();
             foreach($store_details as $astore)
                {

                    $websiteId = $astore->getWebsiteId();
                    $web = Mage::app()->getWebsite($websiteId)->getName();  
                    echo  $web ."<br>";     
                    $data = $astore->getGroupId();
                    echo $data = '&nbsp;&nbsp;&nbsp;'. Mage::app()->getGroup($data)->getName().'<br />';

                    echo $data ='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$astore->getName().'<br />';
                }
            }
            else{
                $ids = explode(',', $stores);
                //echo "<pre>";print_r( $ids);
                foreach($ids as $storeID){
                        //Website details
                    $store = Mage::getModel('core/website')->load($storeID)->getWebsiteId();
                    $website_details = Mage::app()->getWebsite($store);
                    $web = $website_details->getName(); 
                    echo '&nbsp;&nbsp;&nbsp;'. $web ."<br>";         
                        //Store_view details
                    $store = Mage::getModel('core/store')->load($storeID);
                    $store_view =$store->getGroupId();
                    $store_details = Mage::app()->getGroup($store_view);
                    $store_view_name = $store_details->getName();   
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $store_view_name."<br>" ;
                    $name= $store->getName();?>
                    <?php //echo $store_view_name ?>
                    <?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $name ?><br>
            <?php }         
            }

    }

}
