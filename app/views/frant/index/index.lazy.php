<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lazybook</title>
    <link rel="icon" href="/img/lazybook.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/img/lazybook.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/bootstrap4.min.css">
    <!--    <link rel="stylesheet" href="/css/bootstrap-select.css">-->
    <link href="http://www.jq22.com/jquery/font-awesome.4.6.0.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/css/sidebar-menu.css">
    <link rel="stylesheet" href="/css/lazybook.css">
    <link rel="stylesheet" href="/css/fileinput-rtl-4.4.8.css">
    <link rel="stylesheet" href="/css/jquery-select2.min.css">
</head>
<body>


<div class="container-fluid">
    <div class="row" id="global-search-row">
        <div class="col-2"></div>
        <div class="col-8">
            <select id="top-search" class="form-control"></select>
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row" id="lazybook-main">
        <div class="col-2 main-sidebar" id="menu-list">
            <div id="div_menu" class="sidebar">
            </div>
        </div>
        <div class="col-3" id="lazybook-dir-content">

        </div>
        <div class="col-7" id="lazybook-main-content">

        </div>
    </div>
</div>

<nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
    <a href="/admin/entry/contactme" target="_blank" style="color: #adadad;text-align: center;background: #5e5e5e;">联系管理员</a>
</nav>



<script src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/bootstrap4.min.js"></script>
<!--<script src="/js/bootstrap-select.js"></script>-->
<script src="/js/sidebar-menu.js"></script>
<script src="/js/lazybook.js"></script>
<script src="/js/jquery-select2.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-fileinput/4.4.8/js/fileinput.js"></script>
</body>
</html>