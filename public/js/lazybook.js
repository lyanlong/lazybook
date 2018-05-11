// @admin-only 标记表示仅后台使用，前台只开放查看文件功能


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


$(function () {
    setInterval(function () {
        $("#clock").html(new Date().toLocaleTimeString());
    }, 1000);

    //1.获取菜单树
    $.ajax({
        type: "GET",
        url: "/frant/index/menuTree",
        success: function (result) {
            var menulist = JSON.parse(result);
            if (menulist.status) {
                var showlist = $("<ul class=\"sidebar-menu\"></ul>");
                var childrenNum = menulist.data.children.length;
                showall(menulist.data.children, showlist, childrenNum);
                $("#div_menu").append(showlist);
            } else {
                alert('init error:' + menulist.data);
            }

        }
    });


    //4. 获取指定菜单下所有文件
    $("#lazybook-main").on("click", ".jump-li", function () {
        var jump_url = $(this).attr('data-href');
        $.ajax({
            type: "POST",
            url: '/admin/node/preview',
            data: {url: jump_url},
            success: function (result) {
                if (isJsonString(result)) {
                    var result = JSON.parse(result);
                    if (result.status) {
                        var showHtml = '<ul>';
                        result.data.forEach(function (v, k) {
                            var showname = v.split(/[\\/]{1}/).pop();
                            showHtml += "<li>" + (k + 1) + "&nbsp<i class=\"fa fa-file-text\"></i><a class='look-li' href='#' javascript:; data-href='" + v + "'>" + showname + "</a></li>";
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
                            var edit_banner = "<div id='look-tool-banner' style='text-align: right;'>" +
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


    //点击查看搜索出来的文件的详细内容
    $("#global-search-row").on("click", ".log-search-item", function () {
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
                            var edit_banner = "<div id='look-tool-banner' style='text-align: right;'>" +
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

})