<?php


namespace Magenest\Notification\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class DeleteAttributeNotification implements ObserverInterface
{
    protected $collectionCustomer;
    protected $customerRepository;
    protected $json;
    protected $serialize;
    public function __construct
    (
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionCustomer,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Serialize\Serializer\Serialize $serialize
    )
    {
        $this->customerRepository = $customerRepository;
        $this->collectionCustomer = $collectionCustomer;
        $this->json = $json;
        $this->serialize = $serialize;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $dataId = $observer->getArrayid();

        $listCustomer = $this->collectionCustomer->create()->getData();


        forEach($listCustomer as $customer)
        {
            $customerId = $customer['entity_id'];
            $customer = $this->customerRepository->getById($customerId);
            $data = [];
            if(count($customer->getCustomAttribute('notification_received')) != 0)
            {
                $data = $customer->getCustomAttribute('notification_received')->getValue();
                $data = $this->serialize->unserialize($data);
                $dataView = $customer->getCustomAttribute('notification_viewed')->getValue();
                $dataView = $this->serialize->unserialize($dataView);
                forEach($dataId as $id)
                {
                    $key = array_search($id, $data);
                    if($key !== false)
                        unset($data[$key]);
                    $keyView = array_search($id, $dataView);
                    if($keyView !== false)
                        unset($dataView[$keyView]);
                }
                $data = $this->serialize->serialize($data);
                $dataView = $this->serialize->serialize($dataView);
                $customer->setCustomAttribute('notification_received',$data)
                         ->setCustomAttribute('notification_viewed', $dataView);
                $customer = $this->customerRepository->save($customer);
            }
        }






    }
}