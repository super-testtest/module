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
use Lof\ProductTags\Model\ResourceModel\Relatedtag\CollectionFactory;

class Pendingmassdelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    

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
         $itemIds = $this->getRequest()->getPost('id');
        foreach ($itemIds as $itemId) {
            
            //delete from lof_producttags_product table and lof_producttags_tag
            $model = $this->_objectManager->create('Lof\ProductTags\Model\Tag');
            $model->load($itemId);
            $tag_id = $model->getTagId();
            $tag_title = $model->getTagTitle();
            
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
           
            
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', count($itemIds)));

         $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
