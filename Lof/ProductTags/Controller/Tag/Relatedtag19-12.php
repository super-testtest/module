<?php

namespace Lof\ProductTags\Controller\Tag;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Relatedtag extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Lof\ProductTags\Model\TagFactory $tagModelFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $model = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag');
        $customerSession = $this->_objectManager->get('Magento\Customer\Model\Session');
        $relatedtag = $this->getRequest()->getPost("relatedtag");
         $product_id_related = $this->getRequest()->getPost("product_id_related");
        if($model->load($relatedtag,"relatedtag")){
            if($model->getCustomerId()==$customerSession->getCustomer()->getId() && $model->getProductIdRelated()==$product_id_related){
                $this->messageManager->addError(__('Already you have specify this tag %s for this product'),$relatedtag);

                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
        }
         $model = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag');    
         $modelTag = $this->_objectManager->get('Lof\ProductTags\Model\ResourceModel\Tag');
         try{
            $data = array();
            $data['tag_title'] = 'Bold';
            $modelTag->setData($data);
            $modelTag->save();
         }catch(LocalizedException $e){
            print_r($e);
         }
      
         

         print_r($modelTag->getData());exit();
       
        $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($product_id_related);
        try{
            if($customerSession->isLoggedIn()) {
                $customerId = $customerSession->getCustomer()->getId();
                $customerFactory = $this->_objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
                $customer = $customerFactory->load($customerId);
                $data = array();
                $data['customer_id'] = $customerId;
                $data['first_name'] = $customer->getFirstname();
                $data['middle_name'] = $customer->getMiddlename();
                $data['last_name'] = $customer->getLastname();
                $data['product_name'] =$product->getName() ;
                $data['product_sku'] =$product->getSku() ;
                $data['product_id_related'] = $product_id_related;
                $data['is_approve'] = "unapprove";
                $data['relatedtag'] = $relatedtag;
                $model->setData($data);
                $model->save();
                $modelTag->setTagTitle($relatedtag);
                $modelTag->save();
                $this->messageManager->addSuccess(__('Related Tag Added Successfully'));
            }else{
                $this->messageManager->addError(__('Please Login and then try again to specify related tags'));
            }

            }catch(LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('This Related no longer exists.'));
                return $resultRedirect->setPath('*/*/');  
            }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}