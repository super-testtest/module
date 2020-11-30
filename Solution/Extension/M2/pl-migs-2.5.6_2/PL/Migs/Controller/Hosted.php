<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Migs\Controller;

abstract class Hosted extends \Magento\Framework\App\Action\Action
{
    protected $migsHelper;

    protected $plLogger;

    protected $checkoutSession;

    protected $storeManager;

    protected $orderFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \PL\Migs\Helper\Data $migsHelper,
        \PL\Migs\Logger\Logger $plLogger
    ) {
        parent::__construct($context);
        $this->migsHelper = $migsHelper;
        $this->plLogger = $plLogger;
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->orderFactory = $orderFactory;
    }
}