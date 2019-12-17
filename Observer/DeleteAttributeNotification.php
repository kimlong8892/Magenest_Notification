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
                forEach($dataId as $id)
                {
                    $key = array_search($id, $data);
                    if($key !== false)
                    {
                        unset($data[$key]);
                    }
                }
                $data = $this->serialize->serialize($data);
                $customer->setCustomAttribute('notification_received',$data);
                $customer = $this->customerRepository->save($customer);
            }
        }






    }
}