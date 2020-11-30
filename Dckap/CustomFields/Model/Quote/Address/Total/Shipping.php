<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dckap\CustomFields\Model\Quote\Address\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address\FreeShippingInterface;

class Shipping extends \Magento\Quote\Model\Quote\Address\Total\Shipping
{
   
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {

        parent::collect($quote, $shippingAssignment, $total);

        $address = $shippingAssignment->getShipping()->getAddress();
        $method = $shippingAssignment->getShipping()->getMethod();

        $total->setTotalAmount($this->getCode(), 0);
        $total->setBaseTotalAmount($this->getCode(), 0);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }
	$selectedDate = $quote->getDateCustomShippingField();
        $data = $this->getAssignmentWeightData($address, $shippingAssignment->getItems());
        $address->setItemQty($data['addressQty']);
        $address->setWeight($data['addressWeight']);
        $address->setFreeMethodWeight($data['freeMethodWeight']);
        $addressFreeShipping = (bool)$address->getFreeShipping();
        $isFreeShipping = $this->freeShipping->isFreeShipping($quote, $shippingAssignment->getItems());
        $address->setFreeShipping($isFreeShipping);
        if (!$addressFreeShipping && $isFreeShipping) {
            $data = $this->getAssignmentWeightData($address, $shippingAssignment->getItems());
            $address->setItemQty($data['addressQty']);
            $address->setWeight($data['addressWeight']);
            $address->setFreeMethodWeight($data['freeMethodWeight']);
        }

        $address->collectShippingRates();

        if ($method) {
            foreach ($address->getAllShippingRates() as $rate) {
                if ($rate->getCode() == $method) {
                    $store = $quote->getStore();
                    $amountPrice = $this->priceCurrency->convert(
                        $rate->getPrice(),
                        $store
                    );
		    $total->setTotalAmount($this->getCode(), $amountPrice);
                    $total->setBaseTotalAmount($this->getCode(), $rate->getPrice());
                    $shippingDescription = $rate->getCarrierTitle() . ' - ' . $rate->getMethodTitle();
                    $address->setShippingDescription(trim($shippingDescription, ' -'));
		    if($selectedDate != '')
		    {
			$amountPrice = $amountPrice;
		    }
                    $total->setBaseShippingAmount($rate->getPrice());
                    $total->setShippingAmount($amountPrice);
                    $total->setShippingDescription($address->getShippingDescription());
                    break;
                }
            }
        }
        return $this;
    }
    private function getAssignmentWeightData(AddressInterface $address, array $items): array
    {
        $address->setWeight(0);
        $address->setFreeMethodWeight(0);
        $addressWeight = $address->getWeight();
        $freeMethodWeight = $address->getFreeMethodWeight();
        $addressFreeShipping = (bool)$address->getFreeShipping();
        $addressQty = 0;
        foreach ($items as $item) {
            /**
             * Skip if this item is virtual
             */
            if ($item->getProduct()->isVirtual()) {
                continue;
            }

            /**
             * Children weight we calculate for parent
             */
            if ($item->getParentItem()) {
                continue;
            }

            $itemQty = (float)$item->getQty();
            $itemWeight = (float)$item->getWeight();

            if ($item->getHasChildren() && $item->isShipSeparately()) {
                foreach ($item->getChildren() as $child) {
                    if ($child->getProduct()->isVirtual()) {
                        continue;
                    }
                    $addressQty += $child->getTotalQty();

                    if (!$item->getProduct()->getWeightType()) {
                        $itemWeight = (float)$child->getWeight();
                        $itemQty = (float)$child->getTotalQty();
                        $addressWeight += ($itemWeight * $itemQty);
                        $rowWeight = $this->getItemRowWeight(
                            $addressFreeShipping,
                            $itemWeight,
                            $itemQty,
                            $child->getFreeShipping()
                        );
                        $freeMethodWeight += $rowWeight;
                        $item->setRowWeight($rowWeight);
                    }
                }
                if ($item->getProduct()->getWeightType()) {
                    $addressWeight += ($itemWeight * $itemQty);
                    $rowWeight = $this->getItemRowWeight(
                        $addressFreeShipping,
                        $itemWeight,
                        $itemQty,
                        $item->getFreeShipping()
                    );
                    $freeMethodWeight += $rowWeight;
                    $item->setRowWeight($rowWeight);
                }
            } else {
                if (!$item->getProduct()->isVirtual()) {
                    $addressQty += $itemQty;
                }
                $addressWeight += ($itemWeight * $itemQty);
                $rowWeight = $this->getItemRowWeight(
                    $addressFreeShipping,
                    $itemWeight,
                    $itemQty,
                    $item->getFreeShipping()
                );
                $freeMethodWeight += $rowWeight;
                $item->setRowWeight($rowWeight);
            }
        }

        return [
            'addressQty' => $addressQty,
            'addressWeight' => $addressWeight,
            'freeMethodWeight' => $freeMethodWeight
        ];
    }

    /**
     * Calculates item row weight.
     *
     * @param bool $addressFreeShipping
     * @param float $itemWeight
     * @param float $itemQty
     * @param $freeShipping
     * @return float
     */
    private function getItemRowWeight(
        bool $addressFreeShipping,
        float $itemWeight,
        float $itemQty,
        $freeShipping
    ): float {
        $rowWeight = $itemWeight * $itemQty;
        if ($addressFreeShipping || $freeShipping === true) {
            $rowWeight = 0;
        } elseif (is_numeric($freeShipping)) {
            $freeQty = $freeShipping;
            if ($itemQty > $freeQty) {
                $rowWeight = $itemWeight * ($itemQty - $freeQty);
            } else {
                $rowWeight = 0;
            }
        }
        return (float)$rowWeight;
    }
}
