<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductTags\Controller\Adminhtml\Tag;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Lof\ProductTags\Model\ResourceModel\Tag\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    
    const ADMIN_RESOURCE = 'Lof_ProductTags::Tag';

    protected $filter;
    protected $collectionFactory;
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

   
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $model = $this->_objectManager->create('Lof\ProductTags\Model\Tag');
        foreach ($collection as $item) {
            $model->load($item->getId());
            $tag_id = $model->getTagId();
            $tag_title =$model->getTagTitle();
            $post = $this->_objectManager->create('Lof\ProductTags\Model\Relatedtag')->load($tag_title,"relatedtag");
            $customer_id = $post->getCustomerId();
            if($model->getCustomerId() == $customer_id){
                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
                $connection= $this->_resources->getConnection();
                $themeTable = $this->_resources->getTableName('lof_producttags_product');
                $sql = "Delete from " . $themeTable . " where tag_id=".$tag_id;
                $connection->query($sql);

                $model->delete();
                $post->delete(); 
            }
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
