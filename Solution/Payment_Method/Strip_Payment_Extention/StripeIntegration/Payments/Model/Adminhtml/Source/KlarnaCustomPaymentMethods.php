<?php

namespace StripeIntegration\Payments\Model\Adminhtml\Source;

class KlarnaCustomPaymentMethods
{
    public function toOptionArray()
    {
        return [
            [
                'value' => "payin4",
                'label' => __('Slice it > Pay in 4')
            ],
            [
                'value' => "installments",
                'label' => __('Slice it > Installments')
            ],
        ];
    }
}
