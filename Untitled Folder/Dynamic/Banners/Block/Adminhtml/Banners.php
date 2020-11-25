<?php
/**
 * Adminhtml banners list block
 *
 */
namespace Dynamic\Banners\Block\Adminhtml;

class Banners extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_banners';
        $this->_blockGroup = 'Dynamic_Banners';
        $this->_headerText = __('Banners');
        $this->_addButtonLabel = __('Add New Banners');
        parent::_construct();
        if ($this->_isAllowedAction('Dynamic_Banners::save')) {
            $this->buttonList->update('add', 'label', __('Add New Banners'));
        } else {
            $this->buttonList->remove('add');
        }
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
