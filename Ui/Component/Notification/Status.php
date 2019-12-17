<?php


namespace Magenest\Notification\Ui\Component\Notification;


use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Status extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$items) {
                if($items['status'] == 1)
                {
                    $items['status'] = '<span class="grid-severity-notice">Enable</span>';
                }
                else
                {
                    $items['status'] = '<span class="grid-severity-critical">Disable</span>';
                }
            }
        }
        return $dataSource;
    }
}