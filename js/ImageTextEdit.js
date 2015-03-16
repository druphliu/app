/**
 * Created by druphliu on 2014/12/2.
 */
$().ready(function () {
    var onId = 1;
    $("#js_add_appmsg").click(function () {
        var count = parseInt($("#js_appmsg_preview").children().last().attr('data-id')) + 1;
        var html = '<div id="appmsgItem' + count + '" data-fileid="" data-id="' + count + '" class="appmsg_item js_appmsg_item">' +
            '<img class="js_appmsg_thumb appmsg_thumb" src="">' +
            '<i class="appmsg_thumb default">缩略图</i>' +
            '<h4 class="appmsg_title"><a onclick="return false;" href="javascript:void(0);" target="_blank">标题</a>' +
            '</h4>' +
            '<div class="appmsg_edit_mask">' +
            '<a class="grey js_edit" data-id="' + count + '" onclick="return false;" href="javascript:void(0);"><i class="fa fa-pencil bigger-225"></i></a>' +
            '<a class="grey js_del" data-id="' + count + '" onclick="return false;" href="javascript:void(0);"><i class="fa fa-trash-o bigger-225"></i></a>' +
            '</div>' +
            '</div>';
        $("#js_appmsg_preview").append(html);
    });
    $(document).on('click', '.js_edit', function (ev) {
        onId = $(this).attr('data-id');
        movePosition(onId);
    });
    $(document).on('click', ".js_del", function () {
        //删除有数据的
        var delIndex = 1;
        var id = $(this).attr('data-id');
        var location = $.localStorage('editImageText');
        var locationObj = eval(location);
        for (i = 0; i < locationObj.length; i++) {
            if (locationObj[i].id == id) {
                locationObj.splice(i, 1);
            }
        }
        location = JSON.stringify(locationObj);
        $.localStorage('editImageText', location);
        $("#appmsgItem" + id).remove();
        $("#js_appmsg_preview").children().each(function (i) {
            if ($(this).attr('data-id') == id) {
                delIndex = i;
            }
        });
        length = parseInt($("#js_appmsg_preview").children().length) - 1;
        if (length > delIndex) {
            id = delIndex + 1;
        } else if (length == delIndex) {
            id = delIndex;
        } else {
            id = delIndex - 1;
            id = id <= 0 ? id : 0;
        }
        $("#js_appmsg_preview").children().each(function (i) {
            if (i == id) {
                onId = $(this).attr('data-id');
            }
        });
        movePosition(onId);
    });
    $("#title").keyup(function () {
        var value = $(this).val();
        $("#appmsgItem" + onId + " .appmsg_title a").html(value);
    });
    $("#title").blur(function () {
        var value = $(this).val();
        addLocation(onId, 'title', value);
    })
    $("#imgUrl").blur(function () {
        var src = $(this).val();
        if (src) {
            $("#appmsgItem" + onId + " .js_appmsg_thumb").attr('src', src).show();
            $("#appmsgItem" + onId + " .default").remove();
            addLocation(onId, 'src', src);
        }
    });
    $("#summary").blur(function () {
        addLocation(onId, 'summary', $(this).val());
    });
    $("#url").blur(function () {
        addLocation(onId, 'url', $(this).val());
    });
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            title: {
                required: true,
                maxlength: 50
            },
            imgUrl: {
                required: true,
                url: true
            },
            summary: {
                required: true
            },
            url: {
                required: true,
                url: true
            },
            keywords: {
                required: true,
                remote: {                                          //验证用户名是否存在
                    type: "POST",
                    url: $("#keywords").attr('data-url'),             //servlet
                    data: {
                        isAccurate: function () {
                            return $("#isAccurate").is(':checked') ? 1 : 0;
                        },
                        responseId: function () {
                            return $("#keywords").attr('data-responseId')
                        },
                        wechatId: function () {
                            return $("#keywords").attr('data-wechatId')
                        },
                        type: function () {
                            return $("#keywords").attr('data-type')
                        }
                    },
                    dataFilter: function (data) {
                        var json = JSON.parse(data);
                        if (json.result == 1) {
                            return '"true"';
                        }
                        return "\"" + json.msg + "\"";
                    }
                }
            }
        },
        messages: {
            keywords: {
                required: '关键词不能为空',
                remote: "冲突了"
            },
            title: {
                required: "标题不能为空",
                maxlength: "标题不能过长"
            },
            imgUrl: {
                required: "图片地址不能为空",
                url: "格式有误"
            },
            summary: "描述不能为空",
            url: {
                required: "文章地址不能为空",
                url: "格式有误"
            }
        },
        invalidHandler: function (event, validator) { //display error alert on form submit
            $('.alert-danger', $('.login-form')).show();
        },
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if (element.is(':checkbox') || element.is(':radio')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            }
            else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            }
            else error.insertAfter(element.parent());
        },

        submitHandler: function (form) {
			showLoading();
            var j = 1;
            var data;
            var title = $("#title").val();
            addLocation(onId, 'title', title);
            var src = $("#imgUrl").val();
            addLocation(onId, 'src', src);
            var summary = $("#summary").val();
            addLocation(onId, 'summary', summary);
            var url = $("#url").val();
            addLocation(onId, 'url', url);
            var location = $.localStorage('editImageText');
            var locationObj = eval(location);
            for (i = 0; i < locationObj.length; i++) {
                var title = locationObj[i].title;
                var src = encodeURIComponent(locationObj[i].src);
                var summary = locationObj[i].summary;
                var url = encodeURIComponent(locationObj[i].url);
                var id = locationObj[i].filedId ? locationObj[i].filedId : 0;
                if (locationObj[i].id == 1) {
                    var keywords = $("#keywords").val();
                    var isAccurate = $("#isAccurate").val();
                    data = "ImagetextreplayModel[keywords]=" + keywords + "&ImagetextreplayModel[isAccurate]=" + isAccurate + "&title1=" + title + "&src1=" + src + "&&summary1=" + summary + "&url1=" + url + "&id1=" + id + "&count=" + locationObj.length;
                } else {
                    j++;
                    data += "&title" + j + "=" + title+ "&src" + j + "=" + src + "&&summary" + j + "=" + summary + "&url" + j + "=" + url + "&id" + j + "=" + id;
                }
            }
            $.ajax({
                type: "POST",
                data: data,
                dataType: 'json',
                success: function (result) {
					closeLoading();
                    if (result.status == 1) {
                        alert('编辑成功');
                        window.location.href = result.url;
                    } else {
                        alert(result.msg);
                    }
                }
            });
        },
        invalidHandler: function (form) {
        }
    });
})
function addLocation(id, key, value) {
    var hasElement = false;
    var location = $.localStorage('editImageText');
    var locationObj = eval(location);
    var locationObj = location ? eval(location) : [];
    for (i = 0; i < locationObj.length; i++) {
        if (locationObj[i].id == id) {
            switch (key) {
                case 'title':
                    locationObj[i].title = value;
                    break;
                case 'summary':
                    locationObj[i].summary = value;
                    break;
                case 'src':
                    locationObj[i].src = value;
                    break;
                case 'url':
                    locationObj[i].url = value;
                    break;
            }
            hasElement = true;
        }
    }
    if (!hasElement) {
        var arr = JSON.parse('{"id":' + id + ',"' + key + '":"' + value + '"}');
        locationObj.push(arr);
    }
    location = JSON.stringify(locationObj);
    $.localStorage('editImageText', location);
}
function movePosition(onId) {
    var id = 1;
    $("#js_appmsg_preview").children().each(function (i) {
        if ($(this).attr('data-id') == onId) {
            id = i + 1;
        }
    });
    if (id == 2) {
        var height = 200;
    } else if (id == 1) {
        var height = 0;
    } else {
        var height = 200 + 120 * (id - 2);
    }
    var location = $.localStorage('editImageText');
    var locationObj = location ? eval(location) : [];
    for (i = 0; i < locationObj.length; i++) {
        if (locationObj[i].id == onId) {
            var title = locationObj[i].title;
            var src = locationObj[i].src;
            var summary = locationObj[i].summary;
            var url = locationObj[i].url;
        }
    }
    $("#title").val(title);
    $("#imgUrl").val(src);
    $("#summary").val(summary);
    $("#url").val(url);
    $(".appmsg_editor").css('margin-top', height);
    $(".has-error").removeClass('has-error');
    $(".help-block").remove();
}