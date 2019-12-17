<?php


namespace Magenest\Notification\Controller\Adminhtml\Notification;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $modelPromoNotificationFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magenest\Notification\Model\PromoNotificationFactory $modelPromoNotificationFactory
    ) {
        $this->modelPromoNotificationFactory = $modelPromoNotificationFactory;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {



        if(!isset($this->_request->getParams()['id']))
        {
            $this->_redirect('notification/notification/add');
        }
        if(isset($this->_request->getParams()['name']))
        {
            $name = $this->_request->getParam('name');
            $status = $this->_request->getParam('status');
            $short_description = $this->_request->getParam('short_description');
            $redirect_url = $this->_request->getParam('redirect_url');
            $id = $this->_request->getParam('id');
            $modelPromo = $this->modelPromoNotificationFactory->create()->load($id);
            $modelPromo->setName($name);
            $modelPromo->setStatus($status);
            $modelPromo->setShort_description($short_description);
            $modelPromo->setRedirect_url($redirect_url);
            $modelPromo->save();
            return $this->_redirect('notification/notification/show');
        }


        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}