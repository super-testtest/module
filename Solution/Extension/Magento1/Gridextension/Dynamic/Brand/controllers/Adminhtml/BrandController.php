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
 
class Dynamic_Brand_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action
{

	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('dynamic_brand/brand');
	}
	
    /**
     * Init here
     */
	protected function _initAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('dynamic_brand/brand');
		$this->_addBreadcrumb(Mage::helper('dynamic_brand')->__('Brands'), Mage::helper('dynamic_brand')->__('Brands'));
	}

    /**
     * View grid action
     */
	public function indexAction()
	{
		$this->_initAction();
		$this->renderLayout();
	}

    /**
     * View edit form action
     */
	public function editAction()
	{
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('dynamic_brand/adminhtml_brand_edit'));
		$this->renderLayout();
	}

    /**
     * View new form action
     */
	public function newAction()
	{
		$this->editAction();
	}

    /**
     * Save form action
     */
	public function saveAction()
	{
		if ($this->getRequest()->getPost()) {

			//echo "<pre>";print_r($this->getRequest()->getPost());die;
			try {
				$data = $this->getRequest()->getPost();
				$data['category'] = implode(',',$this->getRequest()->getPost('category')); 
				if (isset($_FILES['image']['name']) and (file_exists($_FILES['image']['tmp_name']))) {
					$uploader = new Varien_File_Uploader('image');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','mp4','flv'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media') . DS . 'brands';
					$uploader->save($path, $_FILES['image']['name']);
					$data['image'] = 'brands/' . $_FILES['image']['name'];
				} else {
					if(isset($data['image']['delete']) && $data['image']['delete'] == 1) {
						$data['image'] = '';
					} else {
						unset($data['image']);
					}
				}
				//save store view
				/*$storeView = $data['stores'];
				$dataStore = "";
				foreach($storeView as $store){
					if($dataStore != "") $dataStore .=",";
					$dataStore .= $store;
				} 
				$data['store_id'] = $dataStore;*/
				$model = Mage::getModel('dynamic_brand/brand');
				$model->setData($data)->setBrandId($this->getRequest()->getParam('id'));
				if ($model->getBrandDate == NULL) {
					$model->setBrandDate(now());
				}
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dynamic_brand')->__('Brand was successfully saved'));
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}

		$this->_redirect('*/*/');
	}

    /**
     * Delete action
     */
	public function deleteAction()
	{
		if ($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('dynamic_brand/brand');
				$model->setBrandId($this->getRequest()->getParam('id'))
				      ->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dynamic_brand')->__('Brand was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}

		$this->_redirect('*/*/');
	}
	
	public function massDeleteAction() {
		$bannerIds = $this->getRequest()->getParam('dynamic_brand');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$model = Mage::getModel('dynamic_brand/brand')->load($bannerId);
					$_helper = Mage::helper('dynamic_brand');
					$model->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannerIds)));
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	public function massStatusAction() {
		$bannerIds =  $this->getRequest()->getParam('dynamic_brand');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$banner = Mage::getSingleton('dynamic_brand/brand')->load($bannerId)->setBrandStatus($this->getRequest()->getParam('status'))->setIsMassupdate(true)->save();
				}
				$this->_getSession()->addSuccess(
						$this->__('Total of %d record(s) were successfully updated', count($bannerIds))
				);
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function exportCsvAction() {
		$fileName = 'dynamic_brand.csv';
		$content = $this->getLayout()->createBlock('dynamic_brand/adminhtml_dynamic_brand_grid')->getCsv();
		$this->_sendUploadResponse($fileName, $content);
	}
	public function exportXmlAction() {
		$fileName = 'dynamic_brand.xml';
		$content = $this->getLayout()->createBlock('dynamic_brand/adminhtml_dynamic_brand_grid')->getXml();
		$this->_sendUploadResponse($fileName, $content);
	}

	
}