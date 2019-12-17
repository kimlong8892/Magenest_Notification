<?php


namespace Magenest\Notification\Controller\Adminhtml\Notification;


use Magenest\Movie\Model\ActorFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Delete extends Action
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
        $selects = $this->_request->getParam('selected');
        $modelActor = $this->modelPromoNotificationFactory->create();
        $countDelete = 0;
        $dataId = [];
        foreach ($selects as $select) {
            $dataId[] = $select;
            $modelActor->load($select);
            $modelActor->delete();
            $countDelete++;
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->messageManager->addSuccess(__('Delete Success %1 Notification', $countDelete));
        $parameters = [
            'arrayid' => $dataId
        ];
        $this->_eventManager->dispatch('before_delete_notification', $parameters);
        return $resultRedirect->setPath('notification/notification/show');
    }
}