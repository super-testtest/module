<?php
namespace Lof\ProductTags\Controller\Adminhtml\Tag;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
class Savependingtag extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $relatedtag = $this->getRequest()->getPost("tag_title");
        $is_approve = $this->getRequest()->getPost("status");
        $tagId = $this->getRequest()->getPost("tag_id");
       
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        try {
            $model = $this->_objectManager->create('Lof\ProductTags\Model\Tag');
            $model->load($tagId);
            $model->setStatus($is_approve);
            $model->setTagTitle($relatedtag);
            $model->setIdentifier($tagId);
            $model->save(); 
            $customer_model = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag')->getCollection();
            $customer_model->addFieldToFilter('tag_id',$tagId);
            $tagIds = $customer_model->getAllIds();
            if($tagIds){
                foreach ($tagIds as $id) {
                    $customer_model_new = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag')->load($id);
                    $customer_model_new->setRelatedtag($relatedtag);
                    $customer_model_new->setIsApprove($is_approve);
                    $customer_model_new->save();
                }   
            }
            $this->messageManager->addSuccess(__('Related Tag Saved Successfully'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('This tag no longer exists.'));
                return $resultRedirect->setPath('*/*/');  
            }
            return $resultRedirect;
        }
    
}
