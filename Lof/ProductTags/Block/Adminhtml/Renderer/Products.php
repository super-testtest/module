<?php
namespace Lof\ProductTags\Block\Adminhtml\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Store\Model\StoreManagerInterface;
 
class Products extends AbstractRenderer
{
   private $_storeManager;
   /**
    * @param \Magento\Backend\Block\Context $context
    * @param array $data
    */
   public function __construct(\Magento\Backend\Block\Context $context, StoreManagerInterface $storemanager, array $data = [])
   {
       $this->_storeManager = $storemanager;
       parent::__construct($context, $data);
       $this->_authorization = $context->getAuthorization();
   }
   /**
    * Renders grid column
    *
    * @param Object $row
    * @return  string
    */
   public function render(\Magento\Framework\DataObject $row){
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $relatedtagFactory = $objectManager->create("\Lof\ProductTags\Model\Relatedtag")->getCollection();
      $relatedtagFactory->addFieldToFilter("relatedtag",$row->getRelatedtag());
      $relatedtagFactory->getSelect()->columns(['count' => new \Zend_Db_Expr('COUNT(id)')])->group('product_id_related');
      return count($relatedtagFactory);
    }
}