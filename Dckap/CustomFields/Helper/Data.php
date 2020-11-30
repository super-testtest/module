<?php

namespace Dckap\CustomFields\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

	const XML_SHIPPING_STATUS = 'shipping_message/general/enable';
	const XML_SHIPPING_DISPLAY_TEXT = 'shipping_message/general/display_text';
	const XML_SHIPPING_RATES = 'shipping_message/general/rates';

	public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}
}