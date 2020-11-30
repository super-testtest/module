<?php

namespace Lof\ProductTags\Controller\Tag;
use Magento\Framework\Controller\ResultFactory;

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
        $relatedtag = $this->getRequest()->getPost("tag_title");
        $product_id_related = $this->getRequest()->getPost("tag_products");
        if($model->load($relatedtag,"relatedtag")){
            if($model->getCustomerId() == $customerSession->getCustomer()->getId() && $model->getProductIdRelated() == $product_id_related){
                $this->messageManager->addError(__('Already you have specify this tag '.$relatedtag.' for this product'));
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
        }
        $modelCollection = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag')->getCollection();
        $modelCollection->addFieldToFilter('relatedtag',$relatedtag);
        $modelCollection->addFieldToFilter('product_id_related',$product_id_related);
        if(!empty($modelCollection->getAllIds())){
            $this->messageManager->addError(__('Already you have specify this tag '.$relatedtag.' for this product'));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        if($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomer()->getId();
            //save data
            $customerFactory = $this->_objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
            $customer = $customerFactory->load($customerId);
             $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($product_id_related);
            $customer_model = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag');
        
            $customer_data = array();
            $customer_data['customer_id'] = $customerId;
            $customer_data['first_name'] = $customer->getFirstname();
            $customer_data['middle_name'] = $customer->getMiddlename();
            $customer_data['last_name'] = $customer->getLastname();
            $customer_data['product_name'] =$product->getName() ;
            $customer_data['product_sku'] =$product->getSku() ;
            $customer_data['product_id_related'] = $product_id_related;
            $customer_data['is_approve'] = 0;
            $customer_data['relatedtag'] = $relatedtag;
            
            
            $model = $this->_objectManager->create('Lof\ProductTags\Model\Tag');
            $savedata = array();
            $model->load($relatedtag,'tag_title');
            $customer_data['tag_id'] = $model->getTagId() ? $model->getTagId() : 0;
            $customer_model->setData($customer_data);
            $customer_model->save();
            $flag=true;
            if(!empty($model->getTagId())){
                $flag=false;
                $model->load($model->getTagId());
                $savedata['number_products'] = $model->getNumberProducts() + 1;
                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
                $connection= $this->_resources->getConnection();
                $themeTable = $this->_resources->getTableName('lof_producttags_product');
                $sql = "select * from " . $themeTable . " where tag_id=".$model->getTagId();
                $result = $connection->fetchAll($sql);
                $product_ids = array_column($result, 'product_id');
                $products=array($product_id_related);
                $final_array = array_merge($product_ids,$products);
                $final = array();
                foreach($final_array as $value){
                    $final[$value] = 0;
                }
                $model->setPostedProducts($final);
                $model->save();
            }else {

                $model = $this->_objectManager->create('Lof\ProductTags\Model\Tag');
                $savedata['status'] = 0;
                $savedata['tag_id'] = null;
                $savedata['customer_id'] = $customerId;
                $savedata['tag_title'] = $relatedtag;
                /*$savedata['identifier'] = preg_replace('/(#)|(%)|(&)|({)|(})|(!)|(@)|(:)|(;)|(,)|(<)|(>)|(=)/', '', $relatedtag);
                $savedata['identifier'] = str_replace(" ","-",trim($relatedtag));*/
                $savedata['identifier'] = null;
                $savedata['number_products'] = 1;
                $model->setData($savedata);
                $model->save();
            }

            $tag_id = $model->getId();
            if($flag==true){
                 $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\App\ResourceConnection');
                $connection= $this->_resources->getConnection();

                $themeTable = $this->_resources->getTableName('lof_producttags_product');
                $sql = "INSERT INTO " . $themeTable . "(tag_id, product_id,position) VALUES (".$tag_id.",".$product_id_related.",0)";
                $connection->query($sql);
                $customer_modelNew = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag')->load($relatedtag,"relatedtag");
                $customer_modelNew->setTagId($tag_id);
                $customer_modelNew->save();
            }
           
            $this->messageManager->addSuccess(__('Related Tag Added Successfully'));
        }else{
            $this->messageManager->addError(__('Please Login and then try again to specify related tags'));
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}