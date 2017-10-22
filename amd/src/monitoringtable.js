define(['jquery'], function ($) {
    return {
        init: function () {
            $(document).ready(function () {
                var more = function () {
                    $(this).parents('tr').next().children().toggle();
                    $(this).toggleClass('block_sibcms_showmore block_sibcms_hidemore');
                };
                var monitoringtable = $('#block_sibcms .block_sibcms_monitoringtable');
                monitoringtable.find('td.block_sibcms_coursestats').hide();
                monitoringtable.find('.block_sibcms_showmore').on('click', more);
            });
        }
    }
});
