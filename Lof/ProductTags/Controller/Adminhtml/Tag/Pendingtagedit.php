<?php
namespace Lof\ProductTags\Controller\Adminhtml\Tag;

class Pendingtagedit extends \Magento\Backend\App\Action
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
        $tag = $this->getRequest()->getParam("tag_id");
        $model = $this->_objectManager->get('Lof\ProductTags\Model\Tag')->load($tag);
        $this->_objectManager->create("Magento\Framework\Registry")->register('producttags',$model);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Pending Tags : ".$model->getTagTitle()));
        
        return $resultPage;
    }
}
