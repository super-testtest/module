<?php

namespace Lof\ProductTags\Model\ResourceModel\Relatedtag;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Lof\ProductTags\Model\Relatedtag', 'Lof\ProductTags\Model\ResourceModel\Relatedtag');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>