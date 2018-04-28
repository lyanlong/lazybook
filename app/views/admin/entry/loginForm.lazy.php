<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>lazyBook</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/singin.css" rel="stylesheet">
</head>
<body>

<div id="lazy-admin-login">
    <form class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" class="form-control" placeholder="Email address" v-model="email" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" class="form-control" placeholder="Password"  v-model="password" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" v-model="remember" > Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" @click.prevent="login">Sign in</button>
    </form>

</div>
<hr>

<script src="/js/vue.js"></script>
<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    var app = new Vue({
        el: "#lazy-admin-login",
        data: {
            email: '',
            password: '',
            remember: false,
        },
        methods:{
            login: function()
            {
                var that = this;
                $.ajax({
                    type:"POST",
                    url:"login",
                    dataType:"json",
                    data: {email: that.email, password: that.password, remember: that.remember},
                    success: function(result) {
                        console.log(result);
                        if(result.status){
                            window.location.href = result.url;
                        }else{
                            alert('登录失败');
                        }
                    }
                })
            }
        }
    });
</script>

</body>
</html>