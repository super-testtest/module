<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Model;

use Magento\Framework\DataObject;

class Hosted extends \Magento\Payment\Model\Method\AbstractMethod
{
    const METHOD_CODE = 'migs_hosted';

    protected $_code = self::METHOD_CODE;

    // Local constants
    const COMMAND_PAY = 'pay';

    const COMMAND_CAPTURE = 'capture';

    const TRANSACTION_SOURCE_INTERNET = 'INTERNET';

    const TRANSACTION_SOURCE_MAILORDER = 'MAILORDER';

    const TRANSACTION_SOURCE_TELORDER = 'TELORDER';

    const SOURCE_SUBTYPE_SINGLE = 'SINGLE';

    const SOURCE_SUBTYPE_INSTALLMENT = 'INSTALLMENT';

    const SOURCE_SUBTYPE_RECURRING = 'RECURRING';

    const MAESTRO_CHQ = 'CHQ';

    const MAESTRO_SAV = 'SAV';

    const VPC_VERSION = '1';

    const VPC_URL = 'https://migs.mastercard.com.au/vpcpay';

    const EPS_SSL = 'ssl';

    const EPS_3D = 'threeDSecure';

    protected $_infoBlockType = 'PL\Migs\Block\Info\Hosted';

    /**
     * @var bool
     */
    protected $_canAuthorize = false;

    /**
     * @var bool
     */
    protected $_canCapture = true;


    /**
     * @var bool
     */
    protected $_canUseInternal = false;

    /**
     * @var bool
     */
    protected $_isInitializeNeeded = true;


    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \PL\Migs\Logger\Logger
     */
    protected $plLogger;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    protected $invoiceSender;

    /**
     * @var \PL\Migs\Helper\Data
     */
    protected $migsHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $resolver;

    /**
     * Hosted constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \PL\Migs\Helper\Data $migsHelper
     * @param \PL\Migs\Logger\Logger $plLogger
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Locale\ResolverInterface $resolver
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\UrlInterface $urlBuilder,
        \PL\Migs\Helper\Data $migsHelper,
        \PL\Migs\Logger\Logger $plLogger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Locale\ResolverInterface $resolver,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
        $this->urlBuilder = $urlBuilder;
        $this->migsHelper = $migsHelper;
        $this->plLogger = $plLogger;
        $this->request = $request;
        $this->jsonHelper = $jsonHelper;
        $this->orderSender = $orderSender;
        $this->invoiceSender = $invoiceSender;
        $this->checkoutSession = $checkoutSession;
        $this->resolver = $resolver;
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function initialize($paymentAction, $stateObject)
    {
        if ($paymentAction == 'order') {
            $order = $this->getInfoInstance()->getOrder();
            $order->setCustomerNoteNotify(false);
            $order->setCanSendNewEmailFlag(false);
            $stateObject->setIsNotified(false);
            $stateObject->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
            $stateObject->setStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
        }
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate()
    {
        /** @noinspection PhpDeprecationInspection */
        parent::validate();
        $paymentInfo = $this->getInfoInstance();
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        if ($paymentInfo instanceof \Magento\Sales\Model\Order\Payment) {
            $paymentInfo->getOrder()->getBaseCurrencyCode();
        } else {
            $paymentInfo->getQuote()->getBaseCurrencyCode();
        }
        return $this;
    }


    public function getCheckoutRedirectUrl()
    {
        return $this->urlBuilder->getUrl('migs/hosted/redirect', ['_secure' => $this->getRequest()->isSecure()]);
    }

    /**
     * Retrieve request object
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getFormUrl()
    {
        //return self::VPC_URL;
        return $this->getConfigData('gateway_url').'/vpcpay';
    }


    public function getMerchantId()
    {
        return trim($this->getConfigData('merchant_id'));
    }

    public function getAccessCode()
    {
        return trim($this->getConfigData('access_code'));
    }

    public function getSecureHash()
    {
        return trim($this->getConfigData('secure_secret'));
    }

    /**
     * @param $order
     * @return string
     */
    public function getOrderDescription($order)
    {
        $description =  "Order #".$order->getIncrementId();
        return $description;
    }

    /**
     * @param $url
     * @return mixed
     */
    protected function cutQueryFromUrl($url)
    {
        $explode = explode('?', $url);
        return $explode[0];
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->urlBuilder->getUrl('migs/hosted/return', ['_secure' => $this->getRequest()->isSecure()]);
    }

    /**
     * @param $order
     * @return float
     */
    public function getGrandTotal($order)
    {
        $amount = $order->getBaseGrandTotal();
        $total = round($amount * 100);
        if($order->getBaseCurrencyCode() == 'JPY' ||
            $order->getBaseCurrencyCode() == 'ITL' ||
            $order->getBaseCurrencyCode() == 'GRD')
        {
            $total = round($amount / 100);
        }
        if ($order->getBaseCurrencyCode() == 'KWD') {
            $total = round($amount* 1000);
        }
        return $total;
    }


    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormFields()
    {
        $paymentInfo = $this->getInfoInstance();
        $order = $paymentInfo->getOrder();

        $formFields = [];
        $formFields['vpc_Version'] = self::VPC_VERSION;
        $formFields['vpc_Command'] = self::COMMAND_PAY;
        $formFields['vpc_Merchant'] = $this->getMerchantId();
        $formFields['vpc_AccessCode'] = $this->getAccessCode();
        $formFields['vpc_MerchTxnRef'] = $order->getIncrementId();
        $formFields['vpc_OrderInfo'] = $this->getOrderDescription($order);
        $formFields['vpc_Amount'] = $this->getGrandTotal($order);
        $formFields['vpc_Currency'] = $order->getBaseCurrencyCode();
        $formFields['vpc_Locale'] = substr($this->resolver->getLocale(), 0, 2);
        $formFields['vpc_ReturnURL'] = $this->cutQueryFromUrl($this->getReturnUrl());
        if ($this->getConfigData('use_3d')) {
            $formFields['vpc_Gateway'] = self::EPS_3D;
        } else {
            $formFields['vpc_Gateway'] = self::EPS_SSL;
        }
        $formFields['vpc_SecureHash'] = $this->getHash($formFields);
        $formFields['vpc_SecureHashType'] = 'SHA256';
        if ($this->getConfigData('debug')) {
            $this->plLogger->debug("REQUEST DATA: ".print_r($formFields,1));
        }
        return $formFields;
    }

    /**
     * @param $formFields
     * @param bool|false $storeId
     * @return string
     */
    public function getHash($formFields)
    {
        ksort($formFields);
        $secureSecret = $this->getSecureHash();
        $hashString='';
        foreach ($formFields as $key => $value) {
            if (strlen($value) > 0) {
                $hashString.= $key . "=" . $value . "&";
            }
        }
        return strtoupper(hash_hmac('SHA256', rtrim($hashString, "&"), pack('H*', $secureSecret)));

    }


    public function acceptTransaction(\Magento\Sales\Model\Order $order, $responseData = [])
    {
        $this->checkOrderStatus($order);
        if ($order->getId()) {
            $additionalData = $this->jsonHelper->jsonEncode($responseData);
            $order->getPayment()->setTransactionId($responseData['vpc_TransactionNo']);
            $order->getPayment()->setLastTransId($responseData['vpc_TransactionNo']);
            $order->getPayment()->setAdditionalInformation('payment_additional_info', $additionalData);
            $order->setStatus(\Magento\Sales\Model\Order::STATE_PROCESSING);
            $note = __('Approved the payment. Transaction ID: "%1"', $responseData['vpc_TransactionNo']);
            $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING);
            $order->addStatusHistoryComment($note);
            $order->setCustomerNoteNotify(true);
            $order->setTotalpaid($order->getBaseGrandTotal());
            $this->orderSender->send($order);
            if (!$order->hasInvoices() && $order->canInvoice()) {
                $invoice = $order->prepareInvoice();
                if ($invoice->getTotalQty() > 0) {
                    $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                    $invoice->setTransactionId($order->getPayment()->getTransactionId());
                    $invoice->register();
                    $invoice->addComment(__('Automatic invoice.'), false);
                    //$this->invoiceSender->send($invoice);
                    $invoice->save();
                }
            }
            $order->save();
        }
    }

    public function denyTransaction(\Magento\Sales\Model\Order $order, $responseData = [])
    {
        if ($order->getId()) {
            $note = 'Your order has been cancelled';
            if (isset($responseData['vpc_TransactionNo'])) {
                $additionalData = $this->jsonHelper->jsonEncode($responseData);
                $note = __('Gateway has declined the payment. Transaction ID: "%1"', $responseData['vpc_TransactionNo']);
                $order->getPayment()->setAdditionalInformation('payment_additional_info', $additionalData);
            }
            if ($order->getState()!= \Magento\Sales\Model\Order::STATE_CANCELED) {
                $order->registerCancellation($note)->save();
            }
            $this->checkoutSession->restoreQuote();
        }
    }

    public function checkOrderStatus(\Magento\Sales\Model\Order $order)
    {
        if ($order->getId()) {
            $state = $order->getState();
            switch ($state) {
                case \Magento\Sales\Model\Order::STATE_HOLDED:
                case \Magento\Sales\Model\Order::STATE_CANCELED:
                case \Magento\Sales\Model\Order::STATE_CLOSED:
                case \Magento\Sales\Model\Order::STATE_COMPLETE:
                    break;
            }
        }
    }

}
