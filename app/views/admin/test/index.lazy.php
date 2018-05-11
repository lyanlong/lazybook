<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lazybook</title>
    <link rel="icon" href="/img/frant.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/img/frant.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/bootstrap4.min.css">
    <!--    <link rel="stylesheet" href="/css/bootstrap-select.css">-->
    <link href="http://www.jq22.com/jquery/font-awesome.4.6.0.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/css/sidebar-menu.css">
    <link rel="stylesheet" href="/css/lazybook-admin-test.css">
    <link rel="stylesheet" href="/css/fileinput-rtl-4.4.8.css">
    <link rel="stylesheet" href="/css/jquery-select2.min.css">
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="/admin"><b>Lazy</b>book</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/frant" target="_blank">前台首页 <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">小工具</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">phpsearial转换器</a>
                    <a class="dropdown-item" href="#">sdk api地址调试</a>
                    <a class="dropdown-item" href="#">json调试</a>
                </div>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true" id="clock"><?= $time ?></i>&nbsp;&nbsp;</a></li>
            <li class="active"><a href="#"><?= $admin ?> &nbsp;&nbsp;<span class="sr-only">(current)</span></a></li>
            <li><a href="/logout">退出</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row" id="global-search-row">
        <div class="col-2"></div>
        <div class="col-8">
            <select id="top-search" class="form-control"></select>
            <button class="btn btn-primary" id="refresh-book-log">更新搜索库</button>
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row" id="frant-main">
        <div class="col-2 main-sidebar" id="menu-list">
            <div id="div_menu" class="sidebar">
            </div>
        </div>
        <div class="col-3" id="frant-dir-content">

        </div>
        <div class="col-7" id="frant-main-content">

        </div>
    </div>
</div>

<nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
    <a href="/admin/entry/contactme" target="_blank" style="color: #adadad;text-align: center;background: #5e5e5e;">联系管理员</a>
</nav>

<div class="modal fade" id="myModal-createDFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="add-folder-modal-title">Add</h4>
            </div>
            <div class="modal-body">
                <p>注意<img src="/img/win_filename_rule.png" class="img-thumbnail"></p>
                <div class="input-group input-group">
                        <span class="input-group-addon" id="basic-addon1">
                            <select id="file_or_dir">
                                <option value="0">文件</option>
                                <option value="1">目录</option>
                            </select>
                        </span>
                    <input type="text" class="form-control" id="add-folder-modal-file" placeholder="请输入目录或文件名" aria-describedby="basic-addon1"  style="height: 50px;">
                </div>
                <div id="alert-warning-create" class="alert alert-warning" role="alert" style="display: none;">文件名 <span id="test"></span> 不合规范</div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
                <button class="btn btn-primary" id="add-folder-modal-submit" type="button">提交</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-article-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="upload-article-modal-title">请选择文件</h4>
            </div>
            <div class="modal-body">
                <input type="file" name="upload_article" id="upload_article" multiple class="file-loading"/>
            </div>
        </div>
    </div>
</div>


<script src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/bootstrap4.min.js"></script>
<!--<script src="/js/bootstrap-select.js"></script>-->
<script src="/js/sidebar-menu.js"></script>
<script src="/js/lazybook-admin.js"></script>
<script src="/js/jquery-select2.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-fileinput/4.4.8/js/fileinput.js"></script>
</body>
</html>