// @admin-only 标记表示仅后台使用，前台只给与查看文件功能
//判断是否为json字符串
function isJsonString(str) {
    try {
        if (typeof JSON.parse(str) == "object") {
            return true;
        }
    } catch (e) {
    }
    return false;
}

//bootstrap fillinput 插件封转  @admin-only
function btstrap_fillinput() {
    var oFile = new Object();

    //初始化fileinput控件（第一次初始化）
    oFile.Init = function (ctrlName, uploadUrl) {
        var control = $('#' + ctrlName);

        //初始化上传控件的样式
        control.fileinput({
            language: 'zh', //设置语言
            uploadUrl: uploadUrl, //上传的地址
            allowedFileExtensions: ['jpg', 'gif', 'png', 'txt', 'log', 'csv', 'xlsx', 'html', 'php', 'py', 'conf', 'doc'],//接收的文件后缀
            showUpload: true, //是否显示上传按钮
            showCaption: false,//是否显示标题
            browseClass: "btn btn-primary", //按钮样式
            //dropZoneEnabled: false,//是否显示拖拽区域
            //minImageWidth: 50, //图片的最小宽度
            //minImageHeight: 50,//图片的最小高度
            //maxImageWidth: 1000,//图片的最大宽度
            //maxImageHeight: 1000,//图片的最大高度
            //maxFileSize: 0,//单位为kb，如果为0表示不限制文件大小
            //minFileCount: 0,
            maxFileCount: 100, //表示允许同时上传的最大文件个数
            enctype: 'multipart/form-data',
            validateInitialCount: true,
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
            uploadExtraData: function (previewId, index) {   //额外参数的关键点
                var obj = {};
                obj.url = $("#upload-article-modal-title").attr('data-id');
                return obj;
            }
        });

        //导入文件上传完成之后的事件
        // data.response 为服务端返回的json格式数据
        $("#upload_article").on("fileuploaded", function (event, data, previewId, index) {
            // var status = data.response.error;
            // alert(status ? status : data.response.msg);
            return;
        });
    }
    return oFile;
}


//无限分类菜单生成
function showall(menu_list, parent, childrenNum) {
    for (var menu in menu_list) {
        if (menu_list[menu].children.length > 0) {
            var li = $("<li></li>");
            if (childrenNum > 0) {
                li = $("<li class=\"treeview\"></li>");
                childrenNum--;
            }
            $(li).append("<a href=\"#\"><i class=\"fa fa-share\"></i> <span>" + menu_list[menu].name + "</span><i data-id='" + menu_list[menu].id + "' class=\"fa fa-plus add-folder\"></i></a>");
            var nextParent = $("<ul class=\"treeview-menu\"></ul>");
            $(nextParent).appendTo(li);
            $(li).appendTo(parent);
            showall(menu_list[menu].children, nextParent);
        } else {
            $("<li class=\"jump-li\" data-href='" + menu_list[menu].url + "'><a href=\"#\"><i class=\"fa fa-circle-o\"></i>"
                + menu_list[menu].name
                + "<i  data-id='" + menu_list[menu].id + "' class=\"fa fa-plus add-folder\"></i></a></li>").appendTo(parent);
        }
    }
}


//版面切换  @admin-only
function lazyToggle(type) {
    if ('file' == type) {
        $("#tool-content").hide();
        $("#lazybook-dir-content,#lazybook-main-content").show();
    } else {
        $("#lazybook-dir-content,#lazybook-main-content").hide();
        $("#tool-content").show();
    }
}


$(function () {
    setInterval(function () {
        $("#clock").html(new Date().toLocaleTimeString());
    }, 1000);

    //1.获取菜单树
    $.ajax({
        type: "GET",
        url: "/admin/index/menuTree",
        success: function (result) {
            var menulist = JSON.parse(result);
            if (menulist.status) {
                var showlist = $("<ul class=\"sidebar-menu\"></ul>");
                var childrenNum = menulist.data.children.length;
                showall(menulist.data.children, showlist, childrenNum);
                $("#div_menu").append("<li><a href=\"#\"><i class=\"fa fa-folder-open\" aria-hidden=\"true\"></i>添加根目录<i  data-id='0' class=\"fa fa-plus add-folder\"></i></a></li>");// @admin-only
                $("#div_menu").append(showlist);
            } else {
                alert('init error:' + menulist.data);
            }

        }
    });

    //2. 点击添加菜单/添加文件弹出对应的modal   @admin-only
    $("#lazybook-main").on("click", ".add-folder,.add-file", function () {
        var id = $(this).attr('data-id');
        $("#add-folder-modal-title").attr('data-id', id);
        var type = $(this).attr('class') != 'add-file';
        $("#file_or_dir").val(type ? "1" : "0").attr("disabled", true);//通过value值，设置对应的选中项
        $("#myModal-createDFile").modal();
    });

    //3. 添加菜单、添加文件提交操作  @admin-only
    $("#add-folder-modal-submit").on("click", function () {
        var re = /[\\\*\?\|/:"<>]+/;
        var value = $('#add-folder-modal-file').val();
        if (re.test(value)) {
            $('#alert-warning-create').show();
            $('#test').html(value);
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/admin/node/addNode",
            data: {
                'pid': $("#add-folder-modal-title").attr('data-id'),
                'value': value,
                "type": $('#file_or_dir').val()
            },
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        alert('add success');
                        window.location.reload();
                    } else {
                        alert('add fail');
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        })
    });

    //4. 获取指定菜单下所有文件
    $("#lazybook-main").on("click", ".jump-li", function () {
        lazyToggle('file');
        var jump_url = $(this).attr('data-href');
        $.ajax({
            type: "POST",
            url: '/admin/node/preview',
            data: {url: jump_url},
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        // @admin-only
                        var showHtml = '<ul>' +
                                '<li class="add-file" data-id="' + jump_url + '"><i class="fa fa-plus" aria-hidden="true"></i> 添加文件</li>' +
                                '<li class="upload-file" data-id="' + jump_url + '"><i class="fa fa-upload" aria-hidden="true"></i> 上传文件</li>' +
                                '<li class="del-file" data-id="' + jump_url + '"><i class="fa fa-trash" aria-hidden="true"></i> 删除文件</li>' +
                                '<ul style="list-style-type:none;"><div class="checkbox"><input type="checkbox" id="delfilebox-cmd">全选'
                            ;
                        result.data.forEach(function (v, k) {
                            var showname = v.split(/[\\/]{1}/).pop();
                            showHtml += "<li><input type='checkbox' name='delfilebox' value='" + showname + "'>&nbsp;" + (k + 1) + "&nbsp<i class=\"fa fa-file-text\"></i><a class='look-li' href='#' javascript:; data-href='" + v + "'>" + showname + "</a></li>";
                        });
                        showHtml += '</div></ul>';
                        $('#lazybook-dir-content').html(showHtml);
                    } else {
                        alert('no data');
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        });
    });

    //5. 查看指定文件内容
    $("#lazybook-main").on("click", ".look-li", function () {
        // $(this).addClass('active').children().removeClass('active');
        var jump_url = $(this).attr('data-href');
        $.ajax({
            type: "POST",
            url: "/admin/doc/lookup",
            data: {url: jump_url},
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        if (result.data.type == 'image') {
                            var edit_banner = "<div id='look-tool-banner' style='text-align: right;'>" +
                                "<button class='btn btn-default' id='look-tool-banner-close'>关闭</button>" +
                                "</div>";
                            var content = '<div id=\"lazybook-main-img\"><img src="' + result.data.content + '"></div>';
                        } else {
                            // @admin-only
                            var edit_banner = "<div id='look-tool-banner' style='text-align: right;'>" +
                                "<button class='btn btn-default' id='look-tool-banner-save' data-id='" + jump_url + "' style='display: none'>保存</button>" +
                                "<button class='btn btn-default' id='look-tool-banner-edit'>编辑</button>" +
                                "<button class='btn btn-default' id='look-tool-banner-close'>关闭</button>" +
                                "</div>";
                            var content = '<textarea id="lazybook-main-textarea" style="width:100%;height: 100%" readonly>' + result.data.content + '</textarea>';
                        }

                        $('#lazybook-main-content').html(edit_banner + content).show();
                    } else {
                        alert('no data');
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        });
    });

    //关闭当前打开文件
    $("#lazybook-main").on("click", "#look-tool-banner-close", function () {
        $("#lazybook-main-content").hide();
    });

    //编辑当前打开文件  @admin-only
    $("#lazybook-main").on("click", "#look-tool-banner-edit", function () {
        $("#look-tool-banner-edit").toggle();
        $("#look-tool-banner-save").toggle();
        $("#lazybook-main-textarea").attr("readonly", false).focus();
    });

    //保存当前已编辑文件内容   @admin-only
    $("#lazybook-main").on("click", "#look-tool-banner-save", function () {
        var url = $(this).attr("data-id");
        var content = $("#lazybook-main-textarea").val();  //这里用 html 方法获取不到改变的内容。
        $.ajax({
            type: "POST",
            url: "/admin/doc/eidt",
            data: {url: url, data: content},
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        alert("修改成功");
                        $("#look-tool-banner-edit").toggle();
                        $("#look-tool-banner-save").toggle();
                    } else {
                        alert("修改失败");
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        });
    });

    //上传单文件到服务器当前目录  @admin-only
    $("#lazybook-main").on("click", ".upload-file", function () {
        var id = $(this).attr('data-id');
        $("#upload-article-modal-title").attr('data-id', id);
        //0.初始化fileinput
        btstrap_fillinput().Init("upload_article", "/admin/node/syncUpload");
        $("#upload-article-modal").modal();
    });

    //checkbox 全选全部选切换   @admin-only
    $("#lazybook-main").on("click", "#delfilebox-cmd", function () {
        $("input:checkbox[name=delfilebox]").attr("checked", !$("input:checkbox[name=delfilebox]").attr("checked"));
    });

    //批量删除已选中文件     @admin-only
    $("#lazybook-main").on("click", ".del-file", function () {
        var url = $(this).attr('data-id');
        var dellist = [];
        $("input:checkbox[name=delfilebox]:checked").each(function (i) {
            dellist.push($(this).val());
        });
        if (dellist.length < 1) {
            alert("请在需要删除的文件前打勾");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/admin/node/del",
            data: {url: url, dellist: dellist},
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        alert("删除成功");
                        window.location.reload();
                    } else {
                        alert("删除失败");
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        });
    });

    //点击查看搜索出来的文件的详细内容
    $("#global-search-row").on("click", ".log-search-item", function () {
        lazyToggle('file');
        var tmp = $(this).html();
        var jump_url = tmp.replace(/<span class="log-search-item-list">/g, '').replace(/<\/span>/g, '');
        $.ajax({
            type: "POST",
            url: "/admin/doc/lookup",
            data: {url: jump_url, type: 1},
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        if (result.data.type == 'image') {
                            var edit_banner = "<div id='look-tool-banner' style='text-align: right;'>" +
                                "<button class='btn btn-default' id='look-tool-banner-close'>关闭</button>" +
                                "</div>";
                            var content = '<div id=\"lazybook-main-img\"><img src="' + result.data.content + '"></div>';
                        } else {
                            // @admin-only
                            var edit_banner = "<div id='look-tool-banner' style='text-align: right;'>" +
                                "<button class='btn btn-default' id='look-tool-banner-save' data-id='" + jump_url + "' style='display: none'>保存</button>" +
                                "<button class='btn btn-default' id='look-tool-banner-edit'>编辑</button>" +
                                "<button class='btn btn-default' id='look-tool-banner-close'>关闭</button>" +
                                "</div>";
                            var content = '<textarea id="lazybook-main-textarea" style="width:100%;height: 100%" readonly>' + result.data.content + '</textarea>';
                        }

                        $('#lazybook-main-content').html(edit_banner + content).show();
                    } else {
                        alert('no data');
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        });

    });

    /*
     select2
     0. 服务端返回数据格式如下：
     {"items": [
     {"id": "1","text": "\/VMware Workstation\/home网络配置.png"},
     {"id": "2","text": "\/VMware Workstation\/Linux-下chkconfig命令详解_about系统服务_.txt"}
     ]
     }

     ['items' => [{ id: 0, text: 'enhancement' }, { id: 1, text: 'bug' }, { id: 2, text: 'duplicate' }, { id: 3, text: 'invalid' }, { id: 4, text: 'wontfix' }] ]
     1.默认选中初始化时会先依次调用如下函数
     1.1 templateSelection({id: "", text: "Search"}) return repo.full_name || repo.text; =>  这里函数的 text 参数值默认取自 placeholder设置的值。
     1.2 escapeMarkup(Search)   return  Search; =>  这个函数的作用可以理解为一个输出渲染器，即每次结果输出之前都会先到这个函数里渲染控制一下，它会接受 templateSelection 函数返回的数据并进行一些样式的渲染，当然也可以进行输出逻辑的修改
     2.点击select下拉框时会依次调用如下函数
     2.1 templateResult({disabled: true, loading: true, text: "Searching…"}) return state.text; => 此时刚点击下拉框，下拉框中还没来得及输入你要查询的数据，所以此时函数的参数为默认参数 {disabled: true, loading: true, text: "Searching…"}
     2.2 escapeMarkup(Searching…)    return Searching…;
     2.3 如果设置了 minimumInputLength(最小需要输入多少个字符才进行查询) 属性，这里还会再调用一次 escapeMarkup(Please enter 2 or more characters)
     3.输入框中输入内容进行查询时会一次调用如下函数
     3.1 templateResult({disabled: true, loading: true, text: "Searching…"})
     3.2 escapeMarkup(Searching…)    return Searching…;
     3.3 processResults({status: true, data: "", items: {…}}, {term: "php"})  =>  服务端回调处理函数，第一个参数为服务端的回调数据
     3.4 在3.3获取到服务端的回调数据后select2会循环调用函数 templateResult(服务端返回的结果数据中的每一条匹配数据) 来进行格式化数据并调用 escapeMarkup 函数输出到下拉显示框
     eg: templateResult({id: "3", text: "/VMware Workstation/linux-下安装nginx和php.txt"})  return state.text;
     */
    //select2 搜索功能
    $("#top-search").select2({
        ajax: {
            type: 'POST',
            url: "/admin/search/search",
            dataType: 'json',
            delay: 250,
            data: function (params) {//params : select框中输入的相关内容
                return {
                    key: params.term, // 传给服务端数据：左边为字段名，右边为字段值  ( &key=具体搜索内容)
                    page: params.page
                };
            },
            processResults: function (data, params) {//回调数据
                var reg = eval("/" + params.term + "/g");
                data.items.forEach(function (v, k) {
                    data.items[k]['text'] = data.items[k]['text'].replace(reg, "<span class='log-search-item-list'>" + params.term + "</span>");
                });
                return {
                    results: data.items,   //data.items 对应服务端返回数据中的 $result['items'] 部分
                };
            },
            cache: true
        },
        templateResult: function (state) {//select控件得到数据后(html中获得、js中获得、ajax获得...) 执行(格式化下拉选项)， 格式化后返回给下拉显示
            if (!state.id || state.loading) { //id: 下拉项的 value 值
                return state.text; //text: 下拉项的 文本 值
            }
            var markup = state.text;//格式化下拉选项(样式+值)
            return markup;
        },

        templateSelection: function (repo) {  //选中时执行
            return repo.full_name || repo.text;
        },

        escapeMarkup: function (markup) {
            return "<span class='log-search-item' title='点击查看详细内容'>" + markup + "</span>";
        }, //装饰显示值(css、值修改...)

        width: "80%", //设置下拉框的宽度
        placeholder: "Search",
        minimumInputLength: 2,
    });

    //更新搜索库     @admin-only
    $("#refresh-book-log").on('click', function () {
        $.ajax({
            type: "POST",
            url: "/admin/Search/updateDic",
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (false !== result.status) {
                        alert("成功更新 " + result.status + " 条记录");
                    } else {
                        alert("更新失败");
                    }
                } else {
                    alert('session已过期，请重新登录');
                    window.location.reload();
                }
            }
        });
    });

    //点击具体小工具   @admin-only
    $(".tool-dropdown-item").on("click", function () {
        var url = $(this).attr('data-href');
        $.ajax({
            type: "POST",
            url: url,
            success: function (result) {
                // console.log(result);
                $("#tool-content").html(result);
                lazyToggle('tool');
            }
        });
    });
})