<div id="run-code" class="tool-sub-frame">
    <div class="row">
        <div class="col-6">input your code here</div>
        <div class="col-6">emm, result is...</div>
    </div>
    <div class="row">
        <div class="col-6">
            <textarea style="width: 100%; height: 700px; resize: none;" v-model="code"></textarea>
        </div>
        <div class="col-6">
            <textarea style="width: 100%; height: 700px; resize: none;" v-model="result"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <button class="btn" @click.prevent="runcode"><i class="fa fa-download"
                                                            aria-hidden="true"></i><span>Run it</span></button>
        </div>
        <div class="col-6"><input type="text" class="form-control" readonly v-if="info != ''" v-model="info"
                                  style="color: red"></div>
    </div>

</div>

<script src="/js/vue.js"></script>
<script>

    var vm = new Vue({
        el: "#run-code",
        data: {
            code: '',
            result: '',
            info: '',
        },
        methods: {
            runcode: function () {
                this.result = '';
                if (this.code == '') {
                    this.info = "you code nothing!!";
                    return;
                } else {
                    this.info = '';
                }
                var that = this;
                $.ajax({
                    type: "POST",
                    url: "/admin/runcode/php",
//                    dataType: "json",
                    data: {"code": that.code},
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response.status) {
                            that.result = response.result;
                        } else {
                            that.info = "服务器错误";
                        }

                    },
                    error: function (xhr) {
                        console.log(xhr);
                        that.info = "代码语法有误";
                    }
                });
            },
        },
    });
    //    console.log(vm._data);
</script>