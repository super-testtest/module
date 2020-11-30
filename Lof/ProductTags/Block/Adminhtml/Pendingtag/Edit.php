<?php

namespace Lof\ProductTags\Block\Adminhtml\Pendingtag;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cardslider edit block
     *
     * @return void
     */
    protected function _construct()
    {
        
        $this->_objectId = 'tag_id';
        $this->_blockGroup = 'Lof_ProductTags';
        $this->_controller = 'adminhtml_pendingtag';

        parent::_construct();
         $this->buttonList->update('back', 'onclick','setLocation("'.$this->_getBackUrl().'")');
        $this->buttonList->update('save', 'label', __('Save Tag'));
        // $this->buttonList->add(
        //     'saveandcontinue',
        //     [
        //         'label' => __('Save and Continue Edit'),
        //         'class' => 'save',
        //         'data_attribute' => [
        //             'mage-init' => [
        //                 'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
        //             ],
        //         ]
        //     ],
        //     -100
        // );
        $this->buttonList->remove('reset');
        $this->buttonList->remove('delete');
        // $this->buttonList->update('delete', 'label', __('Delete Tag'),'onclick','setLocation("'.$this->getDeleteUrl().'")');
    }
    protected function _getBackUrl()
    {
        return $this->getUrl('lof_producttags/tag/pendingtag');
    }
    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('pendingtag')->getId()) {
            return __("Edit Tag '%1'", $this->escapeHtml($this->_coreRegistry->registry('pendingtag')->getTitle()));
        } else {
            return __('New Tag');
        }
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('cardslider/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }
    public function getSaveUrl()
    {
        return $this->getUrl('lof_producttags/tag/savependingtag');
    }
    public function getDeleteUrl()
    {
        
        return $this->getUrl('lof_producttags/tag/deletetag/',['id' => $this->getRequest()->getParam("id")]);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            };
        ";
        return parent::_prepareLayout();
    }

}