<?php
namespace Lof\ProductTags\Model;

class Relatedtag extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Lof\ProductTags\Model\ResourceModel\Relatedtag');
    }
}
?>