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
 
class Dynamic_Contactus_Adminhtml_ContactusController extends Mage_Adminhtml_Controller_Action
{

	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('dynamic_contactus/contactus');
	}
	
    /**
     * Init here
     */
	protected function _initAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('dynamic_contactus/contactus');
		$this->_addBreadcrumb(Mage::helper('dynamic_contactus')->__('Contactuss'), Mage::helper('dynamic_contactus')->__('Contactuss'));
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
		$this->_addContent($this->getLayout()->createBlock('dynamic_contactus/adminhtml_contactus_edit'));
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
					$path = Mage::getBaseDir('media') . DS . 'contactuss';
					$uploader->save($path, $_FILES['image']['name']);
					$data['image'] = 'contactuss/' . $_FILES['image']['name'];
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
				$model = Mage::getModel('dynamic_contactus/contactus');
				$model->setData($data)->setContactusId($this->getRequest()->getParam('id'));
				if ($model->getContactusDate == NULL) {
					$model->setContactusDate(now());
				}
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dynamic_contactus')->__('Contactus was successfully saved'));
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
				$model = Mage::getModel('dynamic_contactus/contactus');
				$model->setContactusId($this->getRequest()->getParam('id'))
				      ->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dynamic_contactus')->__('Contactus was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}

		$this->_redirect('*/*/');
	}
	
	public function massDeleteAction() {
		$bannerIds = $this->getRequest()->getParam('dynamic_contactus');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$model = Mage::getModel('dynamic_contactus/contactus')->load($bannerId);
					$_helper = Mage::helper('dynamic_contactus');
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
		$bannerIds =  $this->getRequest()->getParam('dynamic_contactus');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$banner = Mage::getSingleton('dynamic_contactus/contactus')->load($bannerId)->setContactusStatus($this->getRequest()->getParam('status'))->setIsMassupdate(true)->save();
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
		$fileName = 'dynamic_contactus.csv';
		$content = $this->getLayout()->createBlock('dynamic_contactus/adminhtml_dynamic_contactus_grid')->getCsv();
		$this->_sendUploadResponse($fileName, $content);
	}
	public function exportXmlAction() {
		$fileName = 'dynamic_contactus.xml';
		$content = $this->getLayout()->createBlock('dynamic_contactus/adminhtml_dynamic_contactus_grid')->getXml();
		$this->_sendUploadResponse($fileName, $content);
	}

	
}