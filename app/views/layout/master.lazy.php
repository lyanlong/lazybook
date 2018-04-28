<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>lazyBook</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
</head>
<body>
<{{name}}>
<hr>
<{{age}}>
<div id="lazy-admin">
    {{test}}
</div>
<hr>
<{{haha}}>

<script src="/js/vue.js"></script>
<script>
    var app = new Vue({
        el: "#lazy-admin",
        data: {
            test: 'qwert',
        },
    });
</script>

</body>
</html>