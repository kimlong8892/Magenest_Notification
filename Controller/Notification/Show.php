<?php


namespace Magenest\Notification\Controller\Notification;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Show extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
//        $this->_view->loadLayout();
//        $this->_view->renderLayout();
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}