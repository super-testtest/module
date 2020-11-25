<?php
namespace Dynamic\Banners\Block\Adminhtml\Banners\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class Image extends AbstractRenderer {

	private $_storeManager;

	/**
	 * @param \Magento\Backend\Block\Context $context 
	 * @param StoreManagerInterface $storeManager 
	 * @param array|array $data 
	 */
	public function __construct(\Magento\Backend\Block\Context $context, StoreManagerInterface $storeManager, array $data = []) {
		$this->_storeManager = $storeManager;
		parent::__construct($context, $data);
		$this->_authorization = $context->getAuthorization();
	}

	public function render(DataObject $row) {
		$mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$imageUrl = $mediaDirectory.'banners/'.basename($this->_getValue($row));
		return '<img src="'.$imageUrl.'" alt="" width="150" style="margin: auto; display: block"/>';
	}
}