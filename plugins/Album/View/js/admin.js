function album_add(title,url) {
    Wind.use("artDialog", function () {
        art.dialog({
            id: 'ID-ALBUMADD',
            title: title,
            fixed: true,
            lock: true,
            width: 400,
            height: 200,
            background: "#CCCCCC",
            opacity: 0.7,
            content: '',
            cancelVal: '取消',
            cancel: true,
            okVal: '保存相册',
            init: function () {
                var $this = this;
                $.get(url,{_t:new Date().getTime() },function (html) {
                            $this.content(html);
                        })
            },
            ok: function () {
                 var $doc = $(this.content());
                var params = {};
                params.title = $doc.find("input[name=title]").val();
                params.description = $doc.find("#description").val();
                params.id = $doc.find("input[name=album_id]").val();
                $.post(url, params, function (data) {
                    if (data.status == 1) {
                        reloadPage(window)
                    } else {
                        art.dialog({
                            content: data.info,
                            icon: 'error',
                            ok: function () {
                                this.title(data.info);
                                return true;
                            }
                        });
                    }

                }, 'json')
                return false;
            }
        });
    });
}

function photo_edit(url) {
    Wind.use("artDialog", function () {
        art.dialog({
            id: 'ID-PHOTOEDIT',
            fixed: true,
            title: '编辑照片信息',
            lock: true,
            width: 400,
            height: 140,
            background: "#CCCCCC",
            opacity: 0.7,
            content: '',
            cancelVal: '取消修改',
            cancel: true,
            okVal: '保存修改',
            ok: function () {
                var $doc = $(this.content());
                var params = {};
                params.title = $doc.find("input[name=title]").val();
                params.description = $doc.find("#description").val();
                params.id = $doc.find("input[name=photo_id]").val();
                $.post(url, params, function (data) {
                    if (data.status == 1) {
                        reloadPage(window)
                    } else {
                        art.dialog({
                            content: data.info,
                            icon: 'error',
                            ok: function () {
                                this.title(data.info);
                                return true;
                            }
                        });
                    }

                }, 'json')
                return false;
            },
            init: function () {
                $this = this;
                $.get(url, function (html) {
                    $this.content(html);
                })


            }
        });
    });
}

/**
swf上传完回调方法
uploadid dialog id
name dialog名称
textareaid 最后数据返回插入的容器id
funcName 回调函数
args 参数
module 所属模块
catid 栏目id
authkey 参数密钥，验证args
**/
function flashupload(uploadid, name, textareaid, funcName, args, module, catid, authkey) {
    var args = args ? '&args=' + args : '';
    var setting = '&module=' + module + '&catid=' + catid ;
    Wind.use("artDialog","iframeTools",function(){
        art.dialog.open(GV.DIMAUB+'index.php?a=swfupload2&m=asset&g=asset' + args + setting, {
        title: name,
        id: uploadid,
        width: '650px',
        height: '420px',
        lock: true,
        fixed: true,
        background:"#CCCCCC",
        opacity:0,
        ok: function() {
            if (funcName) {
                funcName.apply(this, [this, textareaid]);
            } else {
                submit_ckeditor(this, textareaid);
            }
        },
        cancel: true
    });
    });
}

//多图上传，SWF回调函数
function change_images(uploadid, returnid) {
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    var in_filename = d.$("#att-name").html().substring(1);
    var str = $('#' + returnid).html();
    var contents = in_content.split('|');
    var filenames = in_filename.split('|');
    $('#' + returnid + '_tips').css('display', 'none');
    if (contents == '') return true;
    $.each(contents, function(i, n) {
        var ids = parseInt(Math.random() * 10000 + 10 * i);
        var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
        str += "<li id='image" + ids + "'><a href=\"javascript:remove_div('image" + ids + "')\">移除</a>\n\
            <img src='" +n+"' /><div class='list-right'>\n\
            <input type=\"hidden\" class=\"input image-url-input\"  value='"+n+"' name=\"photos_url[]\"> \n\
            <input type=\"text\"  class=\"input image-alt-input\" placeholder=\"输入照片标题...\" value=\""+filename+"\" name=\"photos_title[]\">\n\
            <textarea name=\"photos_description[]\" class=\"input\" placeholder=\"输入照片描述...\"></textarea></li>";
    });
    $('#' + returnid).html(str);
     if(!$("#photos-list").hasClass("show")){
         $("#photos-list").show().addClass("show")
     }
 }

//验证地址是否为图片
function IsImg(url) {
    var sTemp;
    var b = false;
    var opt = "jpg|gif|png|bmp|jpeg|zip";
    var s = opt.toUpperCase().split("|");
    for (var i = 0; i < s.length; i++) {
        sTemp = url.substr(url.length - s[i].length - 1);
        sTemp = sTemp.toUpperCase();
        s[i] = "." + s[i];
        if (s[i] == sTemp) {
            b = true;
            break;
        }
    }
    return b;
}

//移除指定id内容
function remove_div(id) {
    $('#' + id).remove();
}

//图片使用dialog查看
function image_priview(img) {
    if(img == ''){
        return;
    }
    if (!IsImg(img)) {
        isalert('选择的类型必须为图片类型！');
        return false;
    }
    Wind.use("artDialog",function(){
        art.dialog({
            title: '图片查看',
            fixed: true,
            width:"600px",
            height: '420px',
            id:"image_priview",
            lock: true,
            background:"#CCCCCC",
            opacity:0,
            content: '<img src="' + img + '" style="max-width:1200px;" />',
            time: 5
        });
    });
}
