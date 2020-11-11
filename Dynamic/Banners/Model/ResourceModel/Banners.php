<?php

namespace Dynamic\Banners\Model\ResourceModel;

/**
 * Banners Resource Model
 */
class Banners extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('banners', 'banners_id');
    }
}
