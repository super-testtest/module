<?php

namespace Lof\ProductTags\Controller\Adminhtml\Tag;

use Magento\Backend\App\Action;

class Pendingtagcustomers extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    private $resultLayoutFactory;

    /**
     * Products constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return true;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
       
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('lof_producttags_pendingtag_edit_customers');
                     // ->setInProducts($this->getRequest()->getPost('index_products', null));

        return $resultLayout;
    }
}
