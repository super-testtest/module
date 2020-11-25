<?php
namespace Dynamic\Banners\Block\Adminhtml\Banners\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('banners');
        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Dynamic_Banners::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('banners_main_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Banners Information')]);

        if ($model->getId()) {
            $fieldset->addField('banners_id', 'hidden', ['name' => 'banners_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        /*$fieldset->addField(
            'sub_title',
            'text',
            [
                'name' => 'sub_title',
                'label' => __('Sub Title'),
                'title' => __('Sub Title'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );
        $fieldset->addField(
            'discount_code',
            'text',
            [
                'name' => 'discount_code',
                'label' => __('Discount Text'),
                'title' => __('Discount Text'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );*/

        /*$fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'required'  => true,
                'disabled' => $isElementDisabled
            ]
        );*/
       /* $fieldset->addField(
            'button_text',
            'text',
            [
                'name' => 'button_text',
                'label' => __('Button Text'),
                'title' => __('Button Text'),
                'required'  => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'target_url',
            'text',
            [
                'name' => 'target_url',
                'label' => __('Button URL'),
                'title' => __('Button URL'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );*/

        $fieldset->addField(
            'sort_order',
            'text',
            array(
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'options' => ['1' => 'Enable', '0' => 'Disabled']
            )
        );

       /* $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField('published_at', 'date', [
            'name'     => 'published_at',
            'date_format' => $dateFormat,
            'image'    => $this->getViewFileUrl('images/grid-cal.gif'),
            'value' => $model->getPublishedAt(),
            'label'    => __('Publishing Date'),
            'title'    => __('Publishing Date'),
            'required' => true
        ]);*/
        
        $this->_eventManager->dispatch('adminhtml_banners_edit_tab_main_prepare_form', ['form' => $form]);
       if($model->getImage()){
            $model->setData('image','banners/'.basename($model->getImage()));    
        }


        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banners Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Banners Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
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
