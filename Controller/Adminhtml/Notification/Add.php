<?php


namespace Magenest\Notification\Controller\Adminhtml\Notification;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Add extends Action
{
    protected $resultPageFactory;
    protected $moviePromoNotificationFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magenest\Notification\Model\PromoNotificationFactory $moviePromoNotificationFactory
    ) {
        $this->moviePromoNotificationFactory = $moviePromoNotificationFactory;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {

        if(isset($this->_request->getParams()['id']))
        {
            $this->_redirect('notification/notification/add');
        }
        if(isset($this->_request->getParams()['name']))
        {
            $name = $this->_request->getParam('name');
            $status = $this->_request->getParam('status');
            $short_description = $this->_request->getParam('short_description');
            $redirect_url = $this->_request->getParam('redirect_url');
            $modelPromo = $this->moviePromoNotificationFactory->create();
            $modelPromo->setName($name);
            $modelPromo->setStatus($status);
            $modelPromo->setShort_description($short_description);
            $modelPromo->setRedirect_url($redirect_url);
            $modelPromo->save();

            $parameters = [
                'id' => $modelPromo->getId()
            ];
            $this->_eventManager->dispatch('before_save_notification', $parameters);
            return $this->_redirect('notification/notification/show');
        }

        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}