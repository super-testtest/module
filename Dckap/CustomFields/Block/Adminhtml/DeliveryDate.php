<?php
namespace Dckap\CustomFields\Block\Adminhtml;
use Magento\Backend\Block\Template;

class DeliveryDate extends Template
{

    public function getOrder()
    {
    	$orderid = $this->getRequest()->getParam('order_id');
    	if($orderid){
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			return $order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($orderid); 
		}
    }
    public function getDeliveryDateEditLink($orderId){
        $label = __('Edit');
        $url = $this->getUrl('dckap/order/deliverydate', ['order_id' => $orderId]);
        return '<a href="' . $this->escapeUrl($url) . '">' . $this->escapeHtml($label) . '</a>';
    }

}