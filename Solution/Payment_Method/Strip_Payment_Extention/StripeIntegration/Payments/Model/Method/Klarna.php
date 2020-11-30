<?php

namespace StripeIntegration\Payments\Model\Method;

use Magento\Framework\Exception\CouldNotSaveException;

class Klarna extends \StripeIntegration\Payments\Model\Method\Api\Sources
{
    const METHOD_CODE = 'stripe_payments_klarna';
    protected $_code = self::METHOD_CODE;
    protected $type = 'klarna';
    protected $_isInitializeNeeded = false;
    protected $_canAuthorize = true;
    protected $_canCapture = true;

    public function assignData(\Magento\Framework\DataObject $data)
    {
        parent::assignData($data);

        $info = $this->getInfoInstance();
        $sourceId = $data->getAdditionalData("source_id");
        $info->setAdditionalInformation('source_id', $sourceId);

        return $this;
    }

    public function associateSourceWithOrder($payment)
    {
        $order = $payment->getOrder();
        $info = $this->getInfoInstance();
        $sourceId = $info->getAdditionalInformation("source_id");

        // Due to the nature of Klarna authorizing the payment at the front-end, we don't have an Order # in the Source
        // metadata, so we instead save it in the cache for 1 hour
        $this->cache->save($data = $order->getIncrementId(), $key = $sourceId, $tags = ["stripe_payments"], $lifetime = 12 * 60 * 60);
    }

    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $this->associateSourceWithOrder($payment);

        return parent::authorize($payment, $amount);
    }

    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $this->associateSourceWithOrder($payment);

        if ($amount > 0)
        {
            $token = $payment->getTransactionId();
            if (empty($token))
                $token = $payment->getLastTransId(); // In case where the transaction was not created during the checkout, i.e. with a Stripe Webhook redirect

            if ($token)
            {
                $token = $this->helper->cleanToken($token);
                try
                {

                    $ch = \Stripe\Charge::retrieve($token);
                    $paymentObject = $ch;
                    $amountToCapture = "amount";
                    $finalAmount = $this->helper->getMultiCurrencyAmount($payment, $amount); // $amount is the base_amount
                    $currency = $payment->getOrder()->getOrderCurrencyCode();
                    $cents = 100;
                    if ($this->helper->isZeroDecimal($currency))
                        $cents = 1;

                    if ($ch->captured)
                    {
                        // In theory this condition should never evaluate, but is added for safety
                        if ($ch->currency != strtolower($currency))
                            $this->helper->dieWithError("This invoice has already been captured in Stripe using a different currency ({$ch->currency}).");

                        $capturedAmount = $ch->amount - $ch->amount_refunded;

                        if ($capturedAmount != round($finalAmount * $cents))
                        {
                            $humanReadableAmount = strtoupper($ch->currency) . " " . round($capturedAmount / $cents, 2);
                            $this->helper->dieWithError("This invoice has already been captured in Stripe for a different amount ($humanReadableAmount). Please cancel and create a new offline invoice for the correct amount.");
                        }

                        // We return instead of trying to capture the payment to simulate an Offline capture
                        return $this;
                    }

                    $paymentObject->capture(array($amountToCapture => round($finalAmount * $cents)));

                    $this->cache->save($value = "1", $key = "admin_captured_" . $paymentObject->id, ["stripe_payments"], $lifetime = 60 * 60);
                }
                catch (\Exception $e)
                {
                    $this->logger->critical($e->getMessage());
                    $this->helper->dieWithError($e->getMessage(), $e);
                }
            }
        }

        return parent::capture($payment, $amount);
    }
}
