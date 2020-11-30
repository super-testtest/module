<?php
namespace Lof\ProductTags\Model\ResourceModel;

class Relatedtag extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('customer_related_tags', 'id');
    }
}
?>