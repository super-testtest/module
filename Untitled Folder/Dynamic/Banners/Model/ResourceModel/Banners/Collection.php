<?php

/**
 * Banners Resource Collection
 */
namespace Dynamic\Banners\Model\ResourceModel\Banners;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dynamic\Banners\Model\Banners', 'Dynamic\Banners\Model\ResourceModel\Banners');
    }
}
