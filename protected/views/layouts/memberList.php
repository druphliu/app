<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-28
 * Time: 下午4:37
 */


?>
<?php $this->beginContent('//layouts/mainMember'); ?>
<?php echo $content ?>
<?php $this->endContent() ?>


<script>
    $().ready(function () {
        $('table th input:checkbox').on('click', function () {
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function () {
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });

        });
        $(".bootbox-confirm").on(ace.click_event, function() {
            var button = $(this);
            bootbox.confirm("确认删除？", function(result) {
                if(result) {
                    window.location = button.attr('rel');
                }
            });
        });
    })
</script>