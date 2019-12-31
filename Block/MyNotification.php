<?php


namespace Magenest\Notification\Block;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Element\Template;

class MyNotification extends Template
{
    protected $_customerSession;
    protected $json;
    protected $notiCollectionFactory;
    protected $serialize;

    public function __construct
    (
        SessionFactory $customerSession,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magenest\Notification\Model\PromoNotificationFactory $notiCollectionFactory,
        \Magento\Framework\Serialize\Serializer\Serialize $serialize,
        Template\Context $context,
        array $data = []
    )
    {
        $this->_customerSession = $customerSession;
        $this->json = $json;
        $this->notiCollectionFactory = $notiCollectionFactory;
        $this->serialize = $serialize;
        parent::__construct($context, $data);
    }
    protected function _prepareLayout()
    {
        $this->pageConfig->setMetaTitle(__('My Notification'));
        if ($this->GetNoti()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $this->GetNoti()
            );
            $this->setChild('pager', $pager);
            $this->GetNoti()->load();
        }
        return parent::_prepareLayout();

    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    public function GetNoti()
    {
        $notiCollection = $this->notiCollectionFactory->create()->getCollection()->addFieldToFilter('status', 1);


        $customer = $this->_customerSession->create();


        $checkNew = false;
        if(isset($customer->getCustomerData()->getCustomAttributes()['notification_received']))
        {
            $notiNews = $customer->getCustomerData()->getCustomAttribute('notification_received')->getValue();
            $notiNews = $this->serialize->unserialize($notiNews);

            forEach($notiNews as $noti)
            {
                $notiCollection->setFlag($noti, 'new');
                $checkNew = true;
            }
            if($checkNew)
            {
                $notiCollection->addFieldToFilter('entity_id', $notiNews);
            }
        }
        $checkView = false;
        if(isset($customer->getCustomerData()->getCustomAttributes()['notification_viewed']))
        {
            $notiViews = $customer->getCustomerData()->getCustomAttribute('notification_viewed')->getValue();
            $notiViews = $this->serialize->unserialize($notiViews);

            forEach($notiViews as $noti)
            {
                $notiCollection->setFlag($noti, 'view');
                $checkView = true;
            }
            if($checkView)
            {
                $notiCollection->addFieldToFilter('entity_id', $notiViews);
            }
        }
        if(!$checkNew && !$checkView)
            return;



        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $notiCollection->setPageSize($pageSize);
        $notiCollection->setCurPage($page);
        return $notiCollection;
    }

    public function CountNotiNew()
    {
        $customer = $this->_customerSession->create();
        if(!isset($customer->getCustomerData()->getCustomAttributes()['notification_received']))
            return 0;
        $notiNews = $customer->getCustomerData()->getCustomAttribute('notification_received')->getValue();
        $notiNews = $this->serialize->unserialize($notiNews);
        return count($notiNews);
    }
}