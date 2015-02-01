/**
 * Created by druphliu on 14-10-16.
 */
$().ready(function(){
    //fix modal force focus
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
        var that = this;
        $(document).on('focusin.modal', function (e) {
            if ($(e.target).hasClass('select2-input')) {
                return true;
            }

            if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                that.$element.focus();
            }
        });
    };
})
/**
 * 检查关键词是否重复
 * @returns {boolean|*}
 */
function keywordsCheck(wechatId,type,url,model,responseId) {
    result = false;
    var keywords = $("#"+model+"_keywords").val();
    var wechatId = wechatId;
    var responseId = responseId;
    var type=type;
    var isAccurate = $("#"+model+"_isAccurate").is(':checked') ? 1 : 0;
    $.ajax({
        type: 'POST',
        url: url,
        data: "keywords=" + keywords + "&wechatId=" + wechatId + "&isAccurate=" + isAccurate+'&responseId='+responseId+'&type='+type,
        dataType: 'json',
        async:false,
        success: function (data) {
            if (data.result != 1) {
                $("#"+model+"_keywords_em_").html(data.msg);
                $("#"+model+"_keywords_em_").show();
                $("#"+model+"_keywords").focus();
                $("#"+model+"_keywords").parents('.form-group').addClass('has-error');
                result = false;
            } else {
                $("#"+model+"_keywords_em_").html('');
                $("#"+model+"_keywords_em_").hide();
                result = true;
            }
        }
    });
    return result;
}
function showLoading(){
	//loading
	$('body').addClass('modal-open');
	$('body').append('<div class="bootbox modal fade in" role="dialog" tabindex="-1" ' +
		'style="display: block;" aria-hidden="false"><div class="modal-dialog" ' +
		'style="width: 100%;text-align: center; padding-top: 80px;">' +
		'<i class="fa fa-spinner fa-spin orange bigger-275"></i>' +
		'</div><div class="modal-backdrop fade in"></div>');
}
function removeLoading(){
	$(".bootbox").remove();
	$(".modal-backdrop").remove();
}