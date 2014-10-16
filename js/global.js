/**
 * Created by druphliu on 14-10-16.
 */
/**
 * 检查关键词是否重复
 * @returns {boolean|*}
 */
function keywordsCheck(wechatId,url,model) {
    result = false;
    var keyword = $("#"+model+"_keywords").val();
    var wechatId = wechatId;
    var isAccurate = $("#"+model+"_isAccurate").is(':checked') ? 1 : 0;
    $.ajax({
        type: 'POST',
        url: url,
        data: "keyword=" + keyword + "&wechatId=" + wechatId + "&isAccurate=" + isAccurate,
        dataType: 'json',
        async:false,
        success: function (data) {
            if (data.result != 1) {
                $("#"+model+"_keywords_em_").html(data.msg);
                $("#"+model+"_keywords_em_").show();
                result = false;
            } else {
                result = true;
            }
        }
    });
    return result;
}