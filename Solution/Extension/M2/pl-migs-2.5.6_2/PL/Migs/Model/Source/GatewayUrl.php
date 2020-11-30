<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Model\Source;

class GatewayUrl implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'https://migs.mastercard.com.au',
                'label' => 'migs.mastercard.com.au',
            ],
            [
                'value' => 'https://migs-mtf.mastercard.com.au',
                'label' => 'migs-mtf.mastercard.com.au',
            ]
        ];
    }
}
