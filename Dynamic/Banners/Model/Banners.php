<?php

namespace Dynamic\Banners\Model;

/**
 * Banners Model
 *
 * @method \Dynamic\Banners\Model\Resource\Page _getResource()
 * @method \Dynamic\Banners\Model\Resource\Page getResource()
 */
class Banners extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dynamic\Banners\Model\ResourceModel\Banners');
    }

}
