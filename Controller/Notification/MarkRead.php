<?php


namespace Magenest\Notification\Controller\Notification;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\SessionFactory;

class MarkRead extends Action
{
    protected $resultPageFactory;
    protected $_customerSession;
    protected $customerRepository;
    protected $json;
    protected $serialize;


    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        SessionFactory $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Serialize\Serializer\Serialize $serialize
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->json = $json;
        $this->serialize = $serialize;
        parent::__construct($context);
    }

    public function execute()
    {
        $idNoti = $this->_request->getParam('id');
        $customer = $this->_customerSession->create();
        $customerId = $customer->getCustomerId();


        $customer = $this->customerRepository->getById($customerId);
        $data = [];
        $data = $customer->getCustomAttribute('notification_received')->getValue();
        $data = $this->serialize->unserialize($data);
        $key = array_search($idNoti, $data);

        unset($data[$key]);
        $data = $this->serialize->serialize($data);
        $customer->setCustomAttribute('notification_received',$data);
        $customer = $this->customerRepository->save($customer);
        $this->SetNotiView($idNoti, $customerId);
    }
    private function SetNotiView($idNoti, $customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        $data = [];


        if(count($customer->getCustomAttribute('notification_viewed')) == 0)
        {
            $data[0] = $idNoti;
        }
        else
        {
            $data = $customer->getCustomAttribute('notification_viewed')->getValue();
            $data = $this->serialize->unserialize($data);
            $data[] = $idNoti;

        }
        $data = $this->serialize->serialize($data);
        $customer->setCustomAttribute('notification_viewed',$data);
        $customer = $this->customerRepository->save($customer);
    }

}