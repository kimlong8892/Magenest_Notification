<?php


namespace Magenest\Notification\Controller\Notification;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\SessionFactory;

class Delete extends Action
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
        $typeNoti = $this->_request->getParam('type');
        $customer = $this->_customerSession->create();
        $customerId = $customer->getCustomerId();


        $customer = $this->customerRepository->getById($customerId);
        $data = [];
        $data = $customer->getCustomAttribute($typeNoti)->getValue();
        $data = $this->serialize->unserialize($data);
        $key = array_search($idNoti, $data);
        unset($data[$key]);
        $data = $this->serialize->serialize($data);
        $customer->setCustomAttribute($typeNoti,$data);
        $customer = $this->customerRepository->save($customer);

    }

}