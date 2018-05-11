<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lazybook</title>
    <link rel="icon" href="/img/frant.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/img/frant.ico" type="image/x-icon"/>
</head>
<body>
<div id="lazy-frant-content">
    <ul v-for="menu in menus">
        <li>
            {{menu.name}}
            <span v-if="menu.children">
                <ul v-for="me in menu.children">
                    {{me.name}}
                </ul>
            </span>
        </li>
    </ul>
    <textarea>{{menus}}</textarea>
</div>

<script src="/js/vue.js"></script>
<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    var app = new Vue({
        el: "#lazy-frant-content",
        data: {
            menus: ''
        },
        methods: {
            menuTree: function (data) {
//                console.log(data);
                if (data) {
                    var ul = '<ul>';
                    data.forEach(function (val, index) {
                        ul += "<li>" + val.name + menuTree(val.children) + "</li>";
                    });
                    ul += '</ul>';
                    return ul;
                }
            }
    },
    mounted: function () {
        var that = this;
        $.ajax({
            type: "POST",
            url: "/frant/index/vueInit",
            data: {menu: 1},
            success: function (result) {
                result = JSON.parse(result);
//                console.log(result.menus);
                that.menus = that.menuTree(result.menus);
            }
        })
    }
    })
    ;
</script>

</body>
</html>