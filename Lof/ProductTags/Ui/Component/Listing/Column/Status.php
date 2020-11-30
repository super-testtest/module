<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductTags\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $cmsPage;

    /**
     * Constructor
     *
     * @param \Magento\Cms\Model\Page $cmsPage
     */
    public function __construct(\Magento\Cms\Model\Page $cmsPage)
    {
        $this->cmsPage = $cmsPage;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        // $availableOptions = $this->cmsPage->getAvailableStatuses();
        // $options = [];
        // foreach ($availableOptions as $key => $value) {

         return [
            ['value' => 1, 'label' => __('Approved')],
            ['value' => 2, 'label' => __('Disabled')],
            ['value' => 0, 'label' => __('Pending')]
            ];
        // ];
        //     $options[0] = [
        //         'label' => "Approved",
        //         'value' => 1,
        //     ];
        //     $options[1] = [
        //         'label' => "Disabled",
        //         'value' => 2,
        //     ];
        //     $options[2] = [
        //         'label' => "Pending",
        //         'value' => 0,
        //     ];
        // // }
        //return $options;
    }
}
