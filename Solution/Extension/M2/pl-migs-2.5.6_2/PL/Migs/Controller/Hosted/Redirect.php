<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Controller\Hosted;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class Redirect extends \PL\Migs\Controller\Hosted
{

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \PL\Migs\Helper\Data $migsHelper,
        \PL\Migs\Logger\Logger $plLogger
    ) {
        parent::__construct(
            $context,
            $orderFactory,
            $checkoutSession,
            $storeManager,
            $migsHelper,
            $plLogger
        );
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }

    protected function getOrder()
    {
        if ($this->checkoutSession->getLastRealOrderId()) {
            $incrementId = $this->checkoutSession->getLastRealOrderId();
            $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
            return $order;
        }
    }

}
