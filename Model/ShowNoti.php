<?php


namespace Magenest\Notification\Model;

use Magento\Cms\Model\Page\Source\PageLayout as BasePageLayout;
class ShowNoti extends BasePageLayout{

    public function toOptionArray()
    {
//        $options = parent::toOptionArray();
//        $remove = [
//            "empty",
//            "1column",
//            "2columns-left",
//            "2columns-right",
//            "3columns",
//        ];
//
//        foreach($options as $key => $layout){
//            if(in_array($layout["value"], $remove)){
//                unset($options[$key]);
//            }
//        }

        return "sadasd";
    }
}