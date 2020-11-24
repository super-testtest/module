<?php

namespace Tecksky\Checkoutsignup\Controller\Index;

use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $_request;
    protected $resultJsonFactory;
	
	/**
     * @param \Magento\Framework\App\Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PageFactory $resultPageFactory,
         \Magento\Framework\App\RequestInterface $request,
         \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
	
    /**
     * Default Banners Index page
     *
     * @return void
     */
    public function execute()
    {
        
        $result = $this->resultJsonFactory->create();
         $resultData = [];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $websiteId = $storeManager->getStore()->getWebsiteId();
        $data = $this->_request->getPostValue();
        
        $firstName = $data['firstname'];
        $lastName = $data['lastname'];
        $email = $data['email'];
        $password = $data['password'];

        $address = array(
            'customer_address_id' => '',
            'prefix' => '',
            'firstname' => $firstName,
            'middlename' => '',
            'lastname' => $lastName,
            'suffix' => '',
            'company' => (isset($data['company']))? $data['company'] : '', 
            'street' => array(
                '0' => $data['street']
            ),
            'city' => $data['city'],
            'country_id' => $data['country_id'], 
            'region' => (isset($data['region']))? $data['region'] : '', 
            'region_id' => (isset($data['region_id']))? $data['region_id'] : '', 
            'postcode' => $data['zipcode'],
            'telephone' => $data['phone'],
            'fax' => '',
            'save_in_address_book' => 1
        );

        $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();

        
        $customer = $customerFactory->setWebsiteId($websiteId)->loadByEmail($email);

        
        if ($customer->getId()) {
             $resultData['status'] = false;
             $resultData['error_message'] = "Customer already exist with this email id";
        } else {
            try {
                $customer = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
                $customer->setWebsiteId($websiteId);
                $customer->setEmail($email);
                $customer->setFirstname($firstName);
                $customer->setLastname($lastName);
                $customer->setPassword($password);

                
                $customer->save();

                $customer->setConfirmation(null);
                $customer->save();

                $customAddress = $objectManager->get('\Magento\Customer\Model\AddressFactory')->create();
                $customAddress->setData($address)
                              ->setCustomerId($customer->getId())
                              ->setIsDefaultBilling('1')
                              ->setIsDefaultShipping('1')
                              ->setSaveInAddressBook('1');

                
                $customAddress->save();

                
                $customerSession = $objectManager->create('Magento\Customer\Model\Session');
                $customerSession->setCustomerAsLoggedIn($customer);
                
                $resultData['status'] = true;
                $resultData['sucusses'] = 'Customer with email '.$email.' is successfully created ';

            } catch (Exception $e) {

                $resultData['status'] = false;
                $resultData[]=$e->getMessage();

            }
        }

        return $result->setData($resultData);
    }
}
