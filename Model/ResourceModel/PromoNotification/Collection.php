<?php


namespace Magenest\Notification\Model\ResourceModel\PromoNotification;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Magenest\Notification\Model\PromoNotification',
            'Magenest\Notification\Model\ResourceModel\PromoNotification');
    }
}