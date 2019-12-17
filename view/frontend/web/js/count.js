require(['jquery'], function ($) {
    $(document).ready(function () {
        if($("#count-notification").html() != undefined)
        {
            $("#count-notification").append("<span id='count-noti-append' style='line-height: 25px; text-align: center; height: 25px; width: 25px; background-color: #bbb; border-radius: 50%; display: inline-block;' class='notifications-counter'> "+count+" </span>");
        }
        else
        {
            $(".nav li.current strong").append("<span id='count-noti-append' style='line-height: 25px; text-align: center; height: 25px; width: 25px; background-color: #bbb; border-radius: 50%; display: inline-block;' class='notifications-counter'> "+count+" </span>");
        }
    });
});