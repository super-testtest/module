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
 
class Dynamic_Brand_SubmitController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		if (!Mage::getStoreConfig('dynamic_brand/general/enable')) $this->_redirect('no-route');
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function saveAction() {
		if (!Mage::getStoreConfig('dynamic_brand/general/enable')) $this->_redirect('no-route');
		$data = $this->getRequest()->getPost();
		$brandData = $data['brand'];
		$media = $_FILES['brandmedia']['name'];
        if (!empty($data)) {
            $session = Mage::getSingleton('core/session', array('name'=>'frontend'));
            /* @var $session Mage_Core_Model_Session */
			$brand = Mage::getModel('dynamic_brand/brand');
			$validate = $brand->validate();
			if ($validate === true) {
				$formId = 'dynamic_brand';
				$magentoVersion = Mage::getVersion();
				if (version_compare($magentoVersion, '1.7', '>=')){
					//version is 1.7 or greater
					$captchaModel = Mage::helper('captcha')->getCaptcha($formId);
					if ($captchaModel->isRequired()) {
						$word = $this->_getCaptchaString($this->getRequest(), $formId);
						if (!$captchaModel->isCorrect($word)) {
							Mage::getSingleton('core/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
							$this->_redirectReferer('');
							return;
						}
					}
				}
			
				try {
					 if(isset($_FILES['brandmedia']['name']) && ($_FILES['brandmedia']['tmp_name'] != NULL)){
						$uploader = new Varien_File_Uploader('brandmedia');
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','mp4','flv')); 
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(true);        
						//$path = Mage::getBaseDir('media') . DS ;
						//$path= Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);     

						$path = Mage::getBaseDir('media') . DS . 'brands';
						$img = $uploader->save($path, 'brands/' . $_FILES['brandmedia']['name']);
					}
					$imgPath = 'brands'. $img['file'];
					$brand->setBrandName($brandData['name']);
					$brand->setBrandEmail($brandData['email']);
					$brand->setBrandText($brandData['content']);
					$brand->setBrandImg($imgPath);
					$brand->save();
					$itemId = $brand->getBrandId(); 
					//send email to store 
					if(Mage::getStoreConfig('dynamic_brand/options/enable_notification')){
						Mage::helper('dynamic_brand')->sendMailToStore($itemId);
					}
								
					$session->addSuccess($this->__('Your brand has been accepted'));
				}catch (Exception $e) {
					$session->setFormData($data);
					$session->addError($this->__('Unable to post brand. Please, try again later !'));
				}
			}else {
				try{
					$session->setFormData($data);
				}catch(Exception $e){
					Mage::log($e->getMessage());
				}                  
				if (is_array($validate)) {                   
					foreach ($validate as $errorMessage) {
						$session->addError($errorMessage);
					}                 
				}
				else {
					$session->addError($this->__('Unable to post brand. Please, try again later !'));
				}
			}	
        }

        if ($redirectUrl = Mage::getSingleton('core/session')->getRedirectUrl(true)) {
            $this->_redirectUrl($redirectUrl);
            return;
        }
        $this->_redirectReferer();		
	}
	
	protected function _getCaptchaString($request, $formId)
    {
        $captchaParams = $request->getPost(Mage_Captcha_Helper_Data::INPUT_NAME_FIELD_VALUE);
        return $captchaParams[$formId];
    }
}