
<?php
$dataarr= array('145000013','145000014');
require_once('app/Mage.php');
Mage::app();
$alldata = array();
$file = fopen("finalcsv/"."allorder".".csv","w");

$flag =0;




/*foreach ($dataarr as $key => $value) {

	$orderids = $value;

	$order = Mage::getModel('sales/order')->loadByIncrementId($orderids);
	$items = $order->getAllItems();

	echo $itemcount = count($items)."<br>";
}die;*/
foreach ($dataarr as $key => $value) {
	# code...
/*$order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($value);
$orderItems = count($order->getAllItems())."<br>";*/
//echo $orderids = $value;die;

//echo $value;die;

//$orderInterface = $objectManager->create('Magento\Sales\Api\Data\OrderInterface'); 
$order = Mage::getModel('sales/order')->loadByIncrementId($value);
//echo  $shippingId = $order->getShippingAddress()die;

/*echo "<pre>";print_r($order->getData());die;
// Fetch whole order information

 echo "<pre>"; print_r($order->getData());die; */
//
//$serializer = $objectManager->get('Magento\Framework\Serialize\SerializerInterface');

$finalData = array();
	//$finalData[] =  array($key=>$key);
$finalData['entity_id'] = $order->getData('entity_id');

$finalData['state'] = $order->getData('state');
$finalData['status'] = $order->getData('status');
$finalData['coupon_code'] = $order->getData('coupon_code');
$finalData['protect_code'] = $order->getData('protect_code');
$finalData['shipping_description'] = $order->getData('shipping_description');
$finalData['is_virtual'] = $order->getData('is_virtual');
$finalData['store_id'] = $order->getData('store_id');
$finalData['customer_id'] = $order->getData('customer_id');
$finalData['base_discount_amount'] = $order->getData('base_discount_amount');
$finalData['base_discount_canceled'] = $order->getData('base_discount_canceled');
$finalData['base_discount_invoiced'] = $order->getData('base_discount_invoiced');
$finalData['base_discount_refunded'] = $order->getData('base_discount_refunded');
$finalData['base_grand_total'] = $order->getData('base_grand_total');
$finalData['base_shipping_amount'] = $order->getData('base_shipping_amount');
$finalData['base_shipping_canceled'] = $order->getData('base_shipping_canceled');
$finalData['base_shipping_invoiced'] = $order->getData('base_shipping_invoiced');
$finalData['base_shipping_refunded'] = $order->getData('base_shipping_refunded');
$finalData['base_shipping_tax_amount'] = $order->getData('base_shipping_tax_amount');
$finalData['base_shipping_tax_refunded'] = $order->getData('base_shipping_tax_refunded');
$finalData['base_subtotal'] = $order->getData('base_subtotal');
$finalData['base_subtotal_canceled'] = $order->getData('base_subtotal_canceled');
$finalData['base_subtotal_invoiced'] = $order->getData('base_subtotal_invoiced');
$finalData['base_subtotal_refunded'] = $order->getData('base_subtotal_refunded');
$finalData['base_tax_amount'] = $order->getData('base_tax_amount');
$finalData['base_tax_canceled'] = $order->getData('base_tax_canceled');
$finalData['base_tax_invoiced'] = $order->getData('base_tax_invoiced');
$finalData['base_tax_refunded'] = $order->getData('base_tax_refunded');
$finalData['base_to_global_rate'] = $order->getData('base_to_global_rate');
$finalData['base_to_order_rate'] = $order->getData('base_to_order_rate');
$finalData['base_total_canceled'] = $order->getData('base_total_canceled');
$finalData['base_total_invoiced'] = $order->getData('base_total_invoiced');
$finalData['base_total_invoiced_cost'] = $order->getData('base_total_invoiced_cost');
$finalData['base_total_offline_refunded'] = $order->getData('base_total_offline_refunded');
$finalData['base_total_online_refunded'] = $order->getData('base_total_online_refunded');
$finalData['base_total_paid'] = $order->getData('base_total_paid');
$finalData['base_total_qty_ordered'] = $order->getData('base_total_qty_ordered');
$finalData['base_total_refunded'] = $order->getData('base_total_refunded');
$finalData['discount_amount'] = $order->getData('discount_amount');
$finalData['discount_canceled'] = $order->getData('discount_canceled');
$finalData['discount_invoiced'] = $order->getData('discount_invoiced');
$finalData['discount_refunded'] = $order->getData('discount_refunded');
$finalData['grand_total'] = $order->getData('grand_total');
$finalData['shipping_amount'] = $order->getData('shipping_amount');
$finalData['shipping_canceled'] = $order->getData('shipping_canceled');
$finalData['shipping_invoiced'] = $order->getData('shipping_invoiced');
$finalData['shipping_refunded'] = $order->getData('shipping_refunded');
$finalData['shipping_tax_amount'] = $order->getData('shipping_tax_amount');
$finalData['shipping_tax_refunded'] = $order->getData('shipping_tax_refunded');
$finalData['store_to_base_rate'] = $order->getData('store_to_base_rate');
$finalData['store_to_order_rate'] = $order->getData('store_to_order_rate');
$finalData['subtotal'] = $order->getData('subtotal');
$finalData['subtotal_canceled'] = $order->getData('subtotal_canceled');
$finalData['subtotal_invoiced'] = $order->getData('subtotal_invoiced');
$finalData['subtotal_refunded'] = $order->getData('subtotal_refunded');
$finalData['tax_amount'] = $order->getData('tax_amount');
$finalData['tax_canceled'] = $order->getData('tax_canceled');
$finalData['tax_invoiced'] = $order->getData('tax_invoiced');
$finalData['tax_refunded'] = $order->getData('tax_refunded');
$finalData['total_canceled'] = $order->getData('total_canceled');
$finalData['total_invoiced'] = $order->getData('total_invoiced');
$finalData['total_offline_refunded'] = $order->getData('total_offline_refunded');
$finalData['total_online_refunded'] = $order->getData('total_online_refunded');
$finalData['total_paid'] = $order->getData('total_paid');
$finalData['total_qty_ordered'] = $order->getData('total_qty_ordered');
$finalData['total_refunded'] = $order->getData('total_refunded');
$finalData['can_ship_partially'] = $order->getData('can_ship_partially');
$finalData['can_ship_partially_item'] = $order->getData('can_ship_partially_item');
$finalData['customer_is_guest'] = $order->getData('customer_is_guest');
$finalData['customer_note_notify'] = $order->getData('customer_note_notify');
$finalData['billing_address_id'] = $order->getData('billing_address_id');
$finalData['customer_group_id'] = $order->getData('customer_group_id');
$finalData['edit_increment'] = $order->getData('edit_increment');
$finalData['email_sent'] = $order->getData('email_sent');
$finalData['forced_shipment_with_invoice'] = $order->getData('forced_shipment_with_invoice');
$finalData['payment_auth_expiration'] = $order->getData('payment_auth_expiration');
$finalData['quote_address_id'] = $order->getData('quote_address_id');
$finalData['quote_id'] = $order->getData('quote_id');
$finalData['shipping_address_id'] = $order->getData('shipping_address_id');
$finalData['adjustment_negative'] = $order->getData('adjustment_negative');
$finalData['adjustment_positive'] = $order->getData('adjustment_positive');
$finalData['base_adjustment_negative'] = $order->getData('base_adjustment_negative');
$finalData['base_adjustment_positive'] = $order->getData('base_adjustment_positive');
$finalData['base_shipping_discount_amount'] = $order->getData('base_shipping_discount_amount');
$finalData['base_subtotal_incl_tax'] = $order->getData('base_subtotal_incl_tax');
$finalData['base_total_due'] = $order->getData('base_total_due');
$finalData['payment_authorization_amount'] = $order->getData('payment_authorization_amount');
$finalData['shipping_discount_amount'] = $order->getData('shipping_discount_amount');
$finalData['subtotal_incl_tax'] = $order->getData('subtotal_incl_tax');
$finalData['total_due'] = $order->getData('total_due');
$finalData['weight'] = $order->getData('weight');
$finalData['customer_dob'] = $order->getData('customer_dob');
$finalData['increment_id'] = $order->getData('increment_id');
$finalData['applied_rule_ids'] = $order->getData('applied_rule_ids');
$finalData['base_currency_code'] = $order->getData('base_currency_code');
$finalData['customer_email'] = $order->getData('customer_email');
$finalData['customer_firstname'] = $order->getData('customer_firstname');
$finalData['customer_lastname'] = $order->getData('customer_lastname');
$finalData['customer_middlename'] = $order->getData('customer_middlename');
$finalData['customer_prefix'] = $order->getData('customer_prefix');
$finalData['customer_suffix'] = $order->getData('customer_suffix');
$finalData['customer_taxvat'] = $order->getData('customer_taxvat');
$finalData['discount_description'] = $order->getData('discount_description');
$finalData['ext_customer_id'] = $order->getData('ext_customer_id');
$finalData['ext_order_id'] = $order->getData('ext_order_id');
$finalData['global_currency_code'] = $order->getData('global_currency_code');
$finalData['hold_before_state'] = $order->getData('hold_before_state');
$finalData['hold_before_status'] = $order->getData('hold_before_status');
$finalData['order_currency_code'] = $order->getData('order_currency_code');
$finalData['original_increment_id'] = $order->getData('original_increment_id');
$finalData['relation_child_id'] = $order->getData('relation_child_id');
$finalData['relation_child_real_id'] = $order->getData('relation_child_real_id');
$finalData['relation_parent_id'] = $order->getData('relation_parent_id');
$finalData['relation_parent_real_id'] = $order->getData('relation_parent_real_id');
$finalData['remote_ip'] = $order->getData('remote_ip');
$finalData['shipping_method'] = $order->getData('shipping_method');
$finalData['store_currency_code'] = $order->getData('store_currency_code');
$finalData['store_name'] = $order->getData('store_name');
$finalData['x_forwarded_for'] = $order->getData('x_forwarded_for');
$finalData['customer_note'] = $order->getData('customer_note');
$finalData['created_at'] = $order->getData('created_at');
$finalData['updated_at'] = $order->getData('updated_at');
$finalData['total_item_count'] = $order->getData('total_item_count');
$finalData['customer_gender'] = $order->getData('customer_gender');
$finalData['discount_tax_compensation_amount'] = $order->getData('discount_tax_compensation_amount');
$finalData['discount_tax_compensation_amount'] = $order->getData('discount_tax_compensation_amount');
$finalData['base_discount_tax_compensation_amount'] = $order->getData('base_discount_tax_compensation_amount');
$finalData['shipping_discount_tax_compensation_amount'] = $order->getData('shipping_discount_tax_compensation_amount');
$finalData['base_shipping_discount_tax_compensation_amnt'] = $order->getData('base_shipping_discount_tax_compensation_amnt');
$finalData['discount_tax_compensation_invoiced'] = $order->getData('discount_tax_compensation_invoiced');
$finalData['base_discount_tax_compensation_invoiced'] = $order->getData('base_discount_tax_compensation_invoiced');
$finalData['discount_tax_compensation_refunded'] = $order->getData('discount_tax_compensation_refunded');
$finalData['base_discount_tax_compensation_refunded'] = $order->getData('base_discount_tax_compensation_refunded');
$finalData['shipping_incl_tax'] = $order->getData('shipping_incl_tax');
$finalData['base_shipping_incl_tax'] = $order->getData('base_shipping_incl_tax');
$finalData['coupon_rule_name'] = $order->getData('coupon_rule_name');
$finalData['gift_message_id'] = $order->getData('gift_message_id');
$finalData['paypal_ipn_customer_notified'] = $order->getData('paypal_ipn_customer_notified');

$finalData['address1'.':entity_id'] = $order->getShippingAddress()->getData('entity_id');
$finalData['address1'.':parent_id'] = $order->getShippingAddress()->getData('parent_id');
$finalData['address1'.':customer_address_id'] = $order->getShippingAddress()->getData('customer_address_id');
$finalData['address1'.':quote_address_id'] = $order->getShippingAddress()->getData('quote_address_id');
$finalData['address1'.':region_id'] = $order->getShippingAddress()->getData('region_id');
$finalData['address1'.':customer_id'] = $order->getShippingAddress()->getData('customer_id');
$finalData['address1'.':fax'] = $order->getShippingAddress()->getData('fax');
$finalData['address1'.':region'] = $order->getShippingAddress()->getData('region');
$finalData['address1'.':postcode'] = $order->getShippingAddress()->getData('postcode');
$finalData['address1'.':lastname'] = $order->getShippingAddress()->getData('lastname');
$finalData['address1'.':street'] = $order->getShippingAddress()->getData('street');
$finalData['address1'.':city'] = $order->getShippingAddress()->getData('city');
$finalData['address1'.':email'] = ($order->getShippingAddress()->getData('email')) ? $order->getShippingAddress()->getData('email') : $order->getData('customer_email');
$finalData['address1'.':telephone'] = $order->getShippingAddress()->getData('telephone');
$finalData['address1'.':country_id'] = $order->getShippingAddress()->getData('country_id');
$finalData['address1'.':firstname'] = $order->getShippingAddress()->getData('firstname');
$finalData['address1'.':address_type'] = $order->getShippingAddress()->getData('address_type');
$finalData['address1'.':prefix'] = $order->getShippingAddress()->getData('prefix');
$finalData['address1'.':middlename'] = $order->getShippingAddress()->getData('middlename');
$finalData['address1'.':company'] = $order->getShippingAddress()->getData('company');
$finalData['address1'.':vat_id'] = $order->getShippingAddress()->getData('vat_id');
$finalData['address1'.':vat_is_valid'] = $order->getShippingAddress()->getData('vat_is_valid');
$finalData['address1'.':vat_request_id'] = $order->getShippingAddress()->getData('vat_request_id');
$finalData['address1'.':vat_request_date'] = $order->getShippingAddress()->getData('vat_request_date');
$finalData['address1'.':vat_request_success'] = $order->getShippingAddress()->getData('vat_request_success');

$finalData['address2'.':entity_id'] = $order->getBillingAddress()->getData('entity_id');
$finalData['address2'.':parent_id'] = $order->getBillingAddress()->getData('parent_id');
$finalData['address2'.':customer_address_id'] = $order->getBillingAddress()->getData('customer_address_id');
$finalData['address2'.':quote_address_id'] = $order->getBillingAddress()->getData('quote_address_id');
$finalData['address2'.':region_id'] = $order->getBillingAddress()->getData('region_id');
$finalData['address2'.':customer_id'] = $order->getBillingAddress()->getData('customer_id');
$finalData['address2'.':fax'] = $order->getBillingAddress()->getData('fax');
$finalData['address2'.':region'] = $order->getBillingAddress()->getData('region');
$finalData['address2'.':postcode'] = $order->getBillingAddress()->getData('postcode');
$finalData['address2'.':lastname'] = $order->getBillingAddress()->getData('lastname');
$finalData['address2'.':street'] = $order->getBillingAddress()->getData('street');
$finalData['address2'.':city'] = $order->getBillingAddress()->getData('city');
$finalData['address2'.':email'] = ($order->getBillingAddress()->getData('email')) ? $order->getBillingAddress()->getData('email') : $order->getData('customer_email');
$finalData['address2'.':telephone'] = $order->getBillingAddress()->getData('telephone');
$finalData['address2'.':country_id'] = $order->getBillingAddress()->getData('country_id');
$finalData['address2'.':firstname'] = $order->getBillingAddress()->getData('firstname');
$finalData['address2'.':address_type'] = $order->getBillingAddress()->getData('address_type');
$finalData['address2'.':prefix'] = $order->getBillingAddress()->getData('prefix');
$finalData['address2'.':middlename'] = $order->getBillingAddress()->getData('middlename');
$finalData['address2'.':company'] = $order->getBillingAddress()->getData('company');
$finalData['address2'.':vat_id'] = $order->getBillingAddress()->getData('vat_id');
$finalData['address2'.':vat_is_valid'] = $order->getBillingAddress()->getData('vat_is_valid');
$finalData['address2'.':vat_request_id'] = $order->getBillingAddress()->getData('vat_request_id');
$finalData['address2'.':vat_request_date'] = $order->getBillingAddress()->getData('vat_request_date');
$finalData['address2'.':vat_request_success'] = $order->getBillingAddress()->getData('vat_request_success');



$finalData['payment1'.':entity_id'] = $order->getPayment()->getData('entity_id');
$finalData['payment1'.':parent_id'] = $order->getPayment()->getData('parent_id');
$finalData['payment1'.':base_shipping_captured'] = $order->getPayment()->getData('base_shipping_captured');
$finalData['payment1'.':shipping_captured'] = $order->getPayment()->getData('shipping_captured');
$finalData['payment1'.':amount_refunded'] = $order->getPayment()->getData('amount_refunded');
$finalData['payment1'.':base_amount_paid'] = $order->getPayment()->getData('base_amount_paid');
$finalData['payment1'.':amount_canceled'] = $order->getPayment()->getData('amount_canceled');
$finalData['payment1'.':base_amount_authorized'] = $order->getPayment()->getData('base_amount_authorized');
$finalData['payment1'.':base_amount_paid_online'] = $order->getPayment()->getData('base_amount_paid_online');
$finalData['payment1'.':base_amount_refunded_online'] = $order->getPayment()->getData('base_amount_refunded_online');
$finalData['payment1'.':base_shipping_amount'] = $order->getPayment()->getData('base_shipping_amount');
$finalData['payment1'.':shipping_amount'] = $order->getPayment()->getData('shipping_amount');
$finalData['payment1'.':amount_paid'] = $order->getPayment()->getData('amount_paid');
$finalData['payment1'.':amount_authorized'] = $order->getPayment()->getData('amount_authorized');
$finalData['payment1'.':base_amount_ordered'] = $order->getPayment()->getData('base_amount_ordered');
$finalData['payment1'.':base_shipping_refunded'] = $order->getPayment()->getData('base_shipping_refunded');
$finalData['payment1'.':shipping_refunded'] = $order->getPayment()->getData('shipping_refunded');
$finalData['payment1'.':base_amount_refunded'] = $order->getPayment()->getData('base_amount_refunded');
$finalData['payment1'.':amount_ordered'] = $order->getPayment()->getData('amount_ordered');
$finalData['payment1'.':base_amount_canceled'] = $order->getPayment()->getData('base_amount_canceled');
$finalData['payment1'.':quote_payment_id'] = $order->getPayment()->getData('quote_payment_id');
$finalData['payment1'.':additional_data'] = $order->getPayment()->getData('additional_data');
$finalData['payment1'.':cc_exp_month'] = $order->getPayment()->getData('cc_exp_month');
$finalData['payment1'.':cc_ss_start_year'] = $order->getPayment()->getData('cc_ss_start_year');
$finalData['payment1'.':echeck_bank_name'] = $order->getPayment()->getData('echeck_bank_name');
$finalData['payment1'.':method'] = $order->getPayment()->getData('method');
$finalData['payment1'.':cc_debug_request_body'] = $order->getPayment()->getData('cc_debug_request_body');
$finalData['payment1'.':cc_secure_verify'] = $order->getPayment()->getData('cc_secure_verify');
$finalData['payment1'.':protection_eligibility'] = $order->getPayment()->getData('protection_eligibility');
$finalData['payment1'.':cc_approval'] = $order->getPayment()->getData('cc_approval');
$finalData['payment1'.':cc_last_4'] = $order->getPayment()->getData('cc_last_4');
$finalData['payment1'.':cc_status_description'] = $order->getPayment()->getData('cc_status_description');
$finalData['payment1'.':echeck_type'] = $order->getPayment()->getData('echeck_type');
$finalData['payment1'.':cc_debug_response_serialized'] = $order->getPayment()->getData('cc_debug_response_serialized');
$finalData['payment1'.':cc_ss_start_month'] = $order->getPayment()->getData('cc_ss_start_month');
$finalData['payment1'.':echeck_account_type'] = $order->getPayment()->getData('echeck_account_type');
$finalData['payment1'.':last_trans_id'] = $order->getPayment()->getData('last_trans_id');
$finalData['payment1'.':cc_cid_status'] = $order->getPayment()->getData('cc_cid_status');
$finalData['payment1'.':cc_owner'] = $order->getPayment()->getData('cc_owner');
$finalData['payment1'.':cc_type'] = $order->getPayment()->getData('cc_type');
$finalData['payment1'.':po_number'] = $order->getPayment()->getData('po_number');
$finalData['payment1'.':cc_exp_year'] = $order->getPayment()->getData('cc_exp_year');
$finalData['payment1'.':cc_status'] = $order->getPayment()->getData('cc_status');
$finalData['payment1'.':echeck_routing_number'] = $order->getPayment()->getData('echeck_routing_number');
$finalData['payment1'.':account_status'] = $order->getPayment()->getData('account_status');
$finalData['payment1'.':anet_trans_method'] = $order->getPayment()->getData('anet_trans_method');
$finalData['payment1'.':cc_debug_response_body'] = $order->getPayment()->getData('cc_debug_response_body');
$finalData['payment1'.':cc_ss_issue'] = $order->getPayment()->getData('cc_ss_issue');
$finalData['payment1'.':echeck_account_name'] = $order->getPayment()->getData('echeck_account_name');
$finalData['payment1'.':cc_avs_status'] = $order->getPayment()->getData('cc_avs_status');
$finalData['payment1'.':cc_number_enc'] = $order->getPayment()->getData('cc_number_enc');
$finalData['payment1'.':cc_trans_id'] = $order->getPayment()->getData('cc_trans_id');
$finalData['payment1'.':address_status'] = $order->getPayment()->getData('address_status');
//$finalData['payment1'.':additional_information'] = 'ser;'.$serializer->serialize($order->getPayment()->getData('additional_information'));
$j=1;

foreach ($order->getAllItems() as $value) {
	if($j<=7){
	$finalData['item'.$j.':item_id'] = $value['item_id'];
	$finalData['item'.$j.':order_id'] = $value['order_id'];
	$finalData['item'.$j.':parent_item_id'] = $value['parent_item_id'];
	$finalData['item'.$j.':quote_item_id'] = $value['quote_item_id'];
	$finalData['item'.$j.':store_id'] = $value['store_id'];
	$finalData['item'.$j.':created_at'] = $value['created_at'];
	$finalData['item'.$j.':updated_at'] = $value['updated_at'];
	$finalData['item'.$j.':product_type'] = $value['product_type'];
	$seroption =  (getoption($value['product_options'])!=null) ? 'ser;'.getoption($value['product_options']) : 'null' ;
	$finalData['item'.$j.':product_options'] = $seroption;
	$finalData['item'.$j.':weight'] = $value['weight'];
	$finalData['item'.$j.':is_virtual'] = $value['is_virtual'];
	$finalData['item'.$j.':sku'] = $value['sku'];
	$finalData['item'.$j.':name'] = $value['name'];
	$finalData['item'.$j.':description'] = $value['description'];
	$finalData['item'.$j.':applied_rule_ids'] = $value['applied_rule_ids'];
	$finalData['item'.$j.':additional_data'] = $value['additional_data'];
	$finalData['item'.$j.':is_qty_decimal'] = $value['is_qty_decimal'];
	$finalData['item'.$j.':no_discount'] = $value['no_discount'];
	$finalData['item'.$j.':qty_backordered'] = $value['qty_backordered'];
	$finalData['item'.$j.':qty_canceled'] = $value['qty_canceled'];
	$finalData['item'.$j.':qty_invoiced'] = $value['qty_invoiced'];
	$finalData['item'.$j.':qty_ordered'] = $value['qty_ordered'];
	$finalData['item'.$j.':qty_refunded'] = $value['qty_refunded'];
	$finalData['item'.$j.':qty_shipped'] = $value['qty_shipped'];
	$finalData['item'.$j.':base_cost'] = $value['base_cost'];
	$finalData['item'.$j.':price'] = $value['price'];
	$finalData['item'.$j.':base_price'] = $value['base_price'];
	$finalData['item'.$j.':original_price'] = $value['original_price'];
	$finalData['item'.$j.':base_original_price'] = $value['base_original_price'];
	$finalData['item'.$j.':tax_percent'] = $value['tax_percent'];
	$finalData['item'.$j.':tax_amount'] = $value['tax_amount'];
	$finalData['item'.$j.':base_tax_amount'] = $value['base_tax_amount'];
	$finalData['item'.$j.':tax_invoiced'] = $value['tax_invoiced'];
	$finalData['item'.$j.':base_tax_invoiced'] = $value['base_tax_invoiced'];
	$finalData['item'.$j.':discount_percent'] = $value['discount_percent'];
	$finalData['item'.$j.':discount_amount'] = $value['discount_amount'];
	$finalData['item'.$j.':base_discount_amount'] = $value['base_discount_amount'];
	$finalData['item'.$j.':discount_invoiced'] = $value['discount_invoiced'];
	$finalData['item'.$j.':base_discount_invoiced'] = $value['base_discount_invoiced'];
	$finalData['item'.$j.':amount_refunded'] = $value['amount_refunded'];
	$finalData['item'.$j.':base_amount_refunded'] = $value['base_amount_refunded'];
	$finalData['item'.$j.':row_total'] = $value['row_total'];
	$finalData['item'.$j.':base_row_total'] = $value['base_row_total'];
	$finalData['item'.$j.':row_invoiced'] = $value['row_invoiced'];
	$finalData['item'.$j.':base_row_invoiced'] = $value['base_row_invoiced'];
	$finalData['item'.$j.':row_weight'] = $value['row_weight'];
	$finalData['item'.$j.':base_tax_before_discount'] = $value['base_tax_before_discount'];
	$finalData['item'.$j.':tax_before_discount'] = $value['tax_before_discount'];
	$finalData['item'.$j.':ext_order_item_id'] = $value['ext_order_item_id'];
	$finalData['item'.$j.':locked_do_invoice'] = $value['locked_do_invoice'];
	$finalData['item'.$j.':locked_do_ship'] = $value['locked_do_ship'];
	$finalData['item'.$j.':price_incl_tax'] = $value['price_incl_tax'];
	$finalData['item'.$j.':base_price_incl_tax'] = $value['base_price_incl_tax'];
	$finalData['item'.$j.':row_total_incl_tax'] = $value['row_total_incl_tax'];
	$finalData['item'.$j.':base_row_total_incl_tax'] = $value['base_row_total_incl_tax'];
	$finalData['item'.$j.':discount_tax_compensation_amount'] = $value['discount_tax_compensation_amount'];
	$finalData['item'.$j.':base_discount_tax_compensation_amount'] = $value['base_discount_tax_compensation_amount'];
	$finalData['item'.$j.':discount_tax_compensation_invoiced'] = $value['discount_tax_compensation_invoiced'];
	$finalData['item'.$j.':base_discount_tax_compensation_invoiced'] = $value['base_discount_tax_compensation_invoiced'];
	$finalData['item'.$j.':discount_tax_compensation_refunded'] = $value['discount_tax_compensation_refunded'];
	$finalData['item'.$j.':base_discount_tax_compensation_refunded'] = $value['base_discount_tax_compensation_refunded'];
	$finalData['item'.$j.':tax_canceled'] = $value['tax_canceled'];
	$finalData['item'.$j.':discount_tax_compensation_canceled'] = $value['discount_tax_compensation_canceled'];
	$finalData['item'.$j.':tax_refunded'] = $value['tax_refunded'];
	$finalData['item'.$j.':base_tax_refunded'] = $value['base_tax_refunded'];
	$finalData['item'.$j.':discount_refunded'] = $value['discount_refunded'];
	$finalData['item'.$j.':base_discount_refunded'] = $value['base_discount_refunded'];
	$finalData['item'.$j.':gift_message_id'] = $value['gift_message_id'];
	$finalData['item'.$j.':gift_message_available'] = $value['gift_message_available'];
	$finalData['item'.$j.':free_shipping'] = $value['free_shipping'];
	$finalData['item'.$j.':weee_tax_applied'] = $value['weee_tax_applied'];
	$finalData['item'.$j.':weee_tax_applied_amount'] = $value['weee_tax_applied_amount'];
	$finalData['item'.$j.':weee_tax_applied_row_amount'] = $value['weee_tax_applied_row_amount'];
	$finalData['item'.$j.':weee_tax_disposition'] = $value['weee_tax_disposition'];
	$finalData['item'.$j.':weee_tax_row_disposition'] = $value['weee_tax_row_disposition'];
	$finalData['item'.$j.':base_weee_tax_applied_amount'] = $value['base_weee_tax_applied_amount'];
	$finalData['item'.$j.':base_weee_tax_applied_row_amnt'] = $value['base_weee_tax_applied_row_amnt'];
	$finalData['item'.$j.':base_weee_tax_disposition'] = $value['base_weee_tax_disposition'];
	$finalData['item'.$j.':base_weee_tax_row_disposition'] = $value['base_weee_tax_row_disposition'];
	
   }
	

	$j++;
}
/*$j=1;
foreach ($transaction as $value) {

	$finalData['paymenttransaction'.$j.':transaction_id'] = $value['transaction_id'];
	$finalData['paymenttransaction'.$j.':parent_id'] = $value['parent_id'];
	$finalData['paymenttransaction'.$j.':payment_id'] = $value['payment_id'];
	$finalData['paymenttransaction'.$j.':order_id'] = $value['order_id'];
	$finalData['paymenttransaction'.$j.':txn_id'] = $value['txn_id'];
	$finalData['paymenttransaction'.$j.':parent_txn_id'] = $value['parent_txn_id'];
	$finalData['paymenttransaction'.$j.':txn_type'] = $value['txn_type'];
	$finalData['paymenttransaction'.$j.':is_closed'] = $value['is_closed'];
	$finalData['paymenttransaction'.$j.':additional_information'] = 'ser;'.$serializer->serialize($value['additional_information']);
	$finalData['paymenttransaction'.$j.':created_at'] = $value['created_at'];

	$j++;
}
*/

/*$k=1;
foreach ($order->getStatusHistories() as $value) {
	$finalData['statushistory'.$k.':entity_id'] = $value['entity_id'];
	$finalData['statushistory'.$k.':parent_id'] = $value['parent_id'];
	$finalData['statushistory'.$k.':is_customer_notified'] = $value['is_customer_notified'];
	$finalData['statushistory'.$k.':is_visible_on_front'] = $value['is_visible_on_front'];
	$finalData['statushistory'.$k.':comment'] = $value['comment'];
	$finalData['statushistory'.$k.':status'] = $value['status'];
	$finalData['statushistory'.$k.':created_at'] = $value['created_at'];
	$finalData['statushistory'.$k.':entity_name'] = $value['entity_name'];
	$finalData['statushistory'.$k.':store_id'] = $value['store_id'];

	$k++;
}*/

$v=1;

/*foreach ($order->getInvoiceCollection() as $value) {
	$finalData['invoice'.$v.':entity_id'] = $value['entity_id'];
	$finalData['invoice'.$v.':store_id'] = $value['store_id'];
	$finalData['invoice'.$v.':base_grand_total'] = $value['base_grand_total'];
	$finalData['invoice'.$v.':shipping_tax_amount'] = $value['shipping_tax_amount'];
	$finalData['invoice'.$v.':tax_amount'] = $value['tax_amount'];
	$finalData['invoice'.$v.':base_tax_amount'] = $value['base_tax_amount'];
	$finalData['invoice'.$v.':store_to_order_rate'] = $value['store_to_order_rate'];
	$finalData['invoice'.$v.':base_shipping_tax_amount'] = $value['base_shipping_tax_amount'];
	$finalData['invoice'.$v.':base_discount_amount'] = $value['base_discount_amount'];
	$finalData['invoice'.$v.':base_to_order_rate'] = $value['base_to_order_rate'];
	$finalData['invoice'.$v.':grand_total'] = $value['grand_total'];
	$finalData['invoice'.$v.':shipping_amount'] = $value['shipping_amount'];
	$finalData['invoice'.$v.':subtotal_incl_tax'] = $value['subtotal_incl_tax'];
	$finalData['invoice'.$v.':base_subtotal_incl_tax'] = $value['base_subtotal_incl_tax'];
	$finalData['invoice'.$v.':store_to_base_rate'] = $value['store_to_base_rate'];
	$finalData['invoice'.$v.':base_shipping_amount'] = $value['base_shipping_amount'];
	$finalData['invoice'.$v.':total_qty'] = $value['total_qty'];
	$finalData['invoice'.$v.':base_to_global_rate'] = $value['base_to_global_rate'];
	$finalData['invoice'.$v.':subtotal'] = $value['subtotal'];
	$finalData['invoice'.$v.':base_subtotal'] = $value['base_subtotal'];
	$finalData['invoice'.$v.':discount_amount'] = $value['discount_amount'];
	$finalData['invoice'.$v.':billing_address_id'] = $value['billing_address_id'];
	$finalData['invoice'.$v.':is_used_for_refund'] = $value['is_used_for_refund'];
	$finalData['invoice'.$v.':order_id'] = $value['order_id'];
	$finalData['invoice'.$v.':email_sent'] = $value['email_sent'];
	$finalData['invoice'.$v.':send_email'] = $value['send_email'];
	$finalData['invoice'.$v.':can_void_flag'] = $value['can_void_flag'];
	$finalData['invoice'.$v.':state'] = $value['state'];
	$finalData['invoice'.$v.':shipping_address_id'] = $value['shipping_address_id'];
	$finalData['invoice'.$v.':store_currency_code'] = $value['store_currency_code'];
	$finalData['invoice'.$v.':transaction_id'] = $value['transaction_id'];
	$finalData['invoice'.$v.':order_currency_code'] = $value['order_currency_code'];
	$finalData['invoice'.$v.':base_currency_code'] = $value['base_currency_code'];
	$finalData['invoice'.$v.':global_currency_code'] = $value['global_currency_code'];
	$finalData['invoice'.$v.':increment_id'] = $value['increment_id'];
	$finalData['invoice'.$v.':created_at'] = $value['created_at'];
	$finalData['invoice'.$v.':updated_at'] = $value['updated_at'];
	$finalData['invoice'.$v.':discount_tax_compensation_amount'] = $value['discount_tax_compensation_amount'];
	$finalData['invoice'.$v.':base_discount_tax_compensation_amount'] = $value['base_discount_tax_compensation_amount'];
	$finalData['invoice'.$v.':shipping_discount_tax_compensation_amount'] = $value['shipping_discount_tax_compensation_amount'];
	$finalData['invoice'.$v.':base_shipping_discount_tax_compensation_amnt'] = $value['base_shipping_discount_tax_compensation_amnt'];
	$finalData['invoice'.$v.':shipping_incl_tax'] = $value['shipping_incl_tax'];
	$finalData['invoice'.$v.':base_shipping_incl_tax'] = $value['base_shipping_incl_tax'];
	$finalData['invoice'.$v.':base_total_refunded'] = $value['base_total_refunded'];
	$finalData['invoice'.$v.':discount_description'] = $value['discount_description'];
	$finalData['invoice'.$v.':customer_note'] = $value['customer_note'];
	$finalData['invoice'.$v.':customer_note_notify'] = $value['customer_note_notify'];
	$finalData['invoice'.$j.':'.'item'.$j.':price'] = $value['price'];

	$v++;
}

$j=1;
foreach ($order->getItems() as $value) {
	$finalData['invoice'.$j.':'.'item'.$j.':entity_id'] = $value['entity_id'];
	$finalData['invoice'.$j.':'.'item'.$j.':parent_id'] = $value['parent_item_id'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_price'] = $value['base_price'];
	$finalData['invoice'.$j.':'.'item'.$j.':tax_amount'] = $value['tax_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_row_total'] = $value['base_row_total'];
	$finalData['invoice'.$j.':'.'item'.$j.':discount_amount'] = $value['discount_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':row_total'] = $value['row_total'];
	$finalData['invoice'.$j.':'.'item'.$j.':price_incl_tax'] = $value['price_incl_tax'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_tax_amount'] = $value['base_tax_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':qty'] = $value['qty'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_cost'] = $value['base_cost'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_row_total_incl_tax'] = $value['base_row_total_incl_tax'];
	$finalData['invoice'.$j.':'.'item'.$j.':row_total_incl_tax'] = $value['row_total_incl_tax'];
	$finalData['invoice'.$j.':'.'item'.$j.':product_id'] = $value['entity_id'];
	$finalData['invoice'.$j.':'.'item'.$j.':order_item_id'] = $value['ext_order_item_id'];
	$finalData['invoice'.$j.':'.'item'.$j.':description'] = $value['description'];
	$finalData['invoice'.$j.':'.'item'.$j.':sku'] = $value['sku'];
	$finalData['invoice'.$j.':'.'item'.$j.':name'] = $value['name'];
	$finalData['invoice'.$j.':'.'item'.$j.':discount_tax_compensation_amount'] = $value['discount_tax_compensation_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_discount_tax_compensation_amount'] = $value['base_discount_tax_compensation_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':weee_tax_applied'] = $value['weee_tax_applied'];
	$finalData['invoice'.$j.':'.'item'.$j.':weee_tax_applied_amount'] = $value['weee_tax_applied_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':weee_tax_applied_row_amount'] = $value['weee_tax_applied_row_amount'];
	$finalData['invoice'.$j.':'.'item'.$j.':weee_tax_disposition'] = $value['weee_tax_disposition'];
	$finalData['invoice'.$j.':'.'item'.$j.':base_weee_tax_applied_amount'] = $value['base_weee_tax_applied_amount'];

	$j++;
}
*/

//fputcsv($file,array_keys($finalData));
//echo "<pre>";print_r($finalData);die;
array_push($alldata, $finalData);
//fputcsv($file,$finalData);

//fclose($file);

}

foreach ($alldata as $key => $value) {
	fputcsv($file,$value);
}
fclose($file);

function getoption($value){
		try {
		        $unserializedData = unserialize($value);
		    } catch (Exception $e) {
		        $unserializedData = null;
		    }

		    if (isset($unserializedData['options'])) {
                foreach ($unserializedData['options'] as $key => $option) {
                    if (is_array($option)
                        && array_key_exists('option_type', $option)
                        && $option['option_type'] === 'file'
                    ) {
                        $optionValue = $option['option_value'] ? unserialize($option['option_value']) :
                            $option['option_value'];
                        $unserializedData['options'][$key]['option_value'] = json_encode($optionValue);
                    }
                }
            }
            if (isset($unserializedData['bundle_selection_attributes'])) {
                $bundleSelectionAttributes = $unserializedData['bundle_selection_attributes'] ?
                    unserialize($unserializedData['bundle_selection_attributes']) :
                    $unserializedData['bundle_selection_attributes'];
                $unserializedData['bundle_selection_attributes'] = json_encode($bundleSelectionAttributes);
            }
            if($unserializedData!=null){
            	$servalue = json_encode($unserializedData);
            }else{
            	$servalue =null;
            }

            return $servalue;
            
}
?>
