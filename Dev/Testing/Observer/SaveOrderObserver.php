<?php
namespace Dev\Testing\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SaveOrderObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        

        $event = $observer->getEvent();
        // Get Order Object
        /* @var $order \Magento\Sales\Model\Order */
        $order = $event->getOrder();
        // Get Quote Object
        /** @var $quote \Magento\Quote\Model\Quote $quote */
        $quote = $event->getQuote();

        if ($quote->getCustomNotes()) {
            $order->setCustomNotes($quote->getCustomNotes());
        }
        return $this;
    }
}