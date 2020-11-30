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
class ReturnAction extends \PL\Migs\Controller\Hosted
{
    protected $hosted;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \PL\Migs\Helper\Data $migsHelper,
        \PL\Migs\Logger\Logger $plLogger,
        \PL\Migs\Model\Hosted $hosted
    ) {
        parent::__construct(
            $context,
            $orderFactory,
            $checkoutSession,
            $storeManager,
            $migsHelper,
            $plLogger
        );
        $this->hosted = $hosted;
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($this->hosted->getConfigData('debug')) {
            $this->plLogger->debug(print_r($params, 1));
        }

        if (isset($params['vpc_TxnResponseCode'])) {
            if (isset($params['vpc_SecureHash']) && $this->validateReceipt($params)) {
                if (isset($params['vpc_MerchTxnRef'])) {
                    $incrementId = $params['vpc_MerchTxnRef'];
                    $txnResponseCode = $params['vpc_TxnResponseCode'];
                    $order = $this->getOrder($incrementId);
                    if ($txnResponseCode =='0' || $txnResponseCode == '00') {
                        $this->hosted->acceptTransaction($order, $params);
                        $this->messageManager->addSuccess(__('Transaction was successful'));
                        $this->_redirect('checkout/onepage/success');
                    } else {
                        $this->hosted->denyTransaction($order, $params);
                        if (isset($params['vpc_Message'])) {
                            $this->messageManager->addError(__('Payment error: %1', $params['vpc_Message']));
                        }
                        $this->_redirect('checkout/cart');
                    }
                }
            } else {
                $order = $this->orderFactory->create()->loadByIncrementId($this->checkoutSession->getLastRealOrderId());
                $this->hosted->denyTransaction($order, $params);
                if (isset($params['vpc_Message'])) {
                    $this->messageManager->addError(__('Payment error: %1', $params['vpc_Message']));
                }
                $this->_redirect('checkout/cart');
            }
        } else {
            $this->messageManager->addError(__('Invalid Data'));
            $this->_redirect('checkout/cart');
        }
    }

    /**
     * @param $params
     * @return bool
     */
    public function validateReceipt($params)
    {
        
        $secure_secret = $this->hosted->getSecureHash();
        ksort($params);
        $hashString='';
        foreach ($params as $key => $value) {
            if ($key != 'vpc_SecureHash' && $key != 'vpc_SecureHashType' && strlen($value) > 0) {
                $hashString .= $key . "=" . $value . "&";
            }
        }
        return strtoupper($params['vpc_SecureHash']) == strtoupper(hash_hmac('SHA256', rtrim($hashString, "&"), pack('H*', $secure_secret)));
    }

    /**
     * @param $incrementId
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder($incrementId)
    {
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        return $order;
    }
}
