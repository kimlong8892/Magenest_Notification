<?php


namespace Magenest\Notification\Block\Adminhtml\Status;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{


    public function toOptionArray()
    {


        $data = [
            [
                'value' => 1,
                'label' => 'Enable'
            ],
            [
                'value' => 0,
                'label' => 'Disable'
            ]
        ];

        return $data;
    }
}