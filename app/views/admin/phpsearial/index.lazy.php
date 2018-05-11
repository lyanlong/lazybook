<div id="php-serial" class="tool-sub-frame">
    <div class="row">
        <div class="col-4">输入服务端 SerialDefine.h 文件内容</div>
        <div class="col-4">生成文件: CDict.php(老版后台)</div>
        <div class="col-4">生成文件: php_serial.json(剑雨系)</div>
    </div>
    <div class="row">
        <div class="col-4">
            <textarea style="width: 100%; height: 700px; resize: none;" v-model="serial_content" @dblclick="mouseenter('serial_content')" title="双击查看完整内容"
                      ref="type"></textarea>
        </div>
        <div class="col-4">
            <textarea style="width: 100%; height: 700px; resize: none;" v-model="cdict_content"
                      @dblclick="mouseenter('cdict_content')" title="双击查看完整内容"></textarea>
        </div>
        <div class="col-4">
            <textarea style="width: 100%; height: 700px; resize: none;" v-model="phpserial_content"
                      @dblclick="mouseenter('phpserial_content')" title="双击查看完整内容"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4"><button class="btn" @click.prevent="download('CDict.php')"><i class="fa fa-download" aria-hidden="true"></i><span>下载</span></button></div>
        <div class="col-4"><button class="btn"  @click.prevent="download('php_serial.json')"><i class="fa fa-download" aria-hidden="true"></i><span>下载</span></button></div>
    </div>


    <div class="modal fade bs-example-modal-lg" id="php-serial-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: 1000px;height: 1000px;">
                <div class="modal-header">
                    <h4 class="modal-title" id="php-serial-modal-title">{{modal.title}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="width: 90%;height: 90%;">
                    <textarea id="php-serial-modal-content"   style="width: 900px;height: 900px;" readonly>
                        {{modal.content}}
                    </textarea>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="/js/vue.js"></script>
<script>
    function downloadFile(fileName, content, blobOptions) {
        blobOptions = blobOptions || {};

        var blob = new Blob([content], blobOptions);
        var a = document.createElement('a');
        a.innerHTML = fileName;

        // 指定生成的文件名
        a.download = fileName;
        a.href = URL.createObjectURL(blob);

        document.body.appendChild(a);

        var evt = document.createEvent("MouseEvents");
        evt.initEvent("click", false, false);

        a.dispatchEvent(evt);

        document.body.removeChild(a);
        return true;
    }

    var vm = new Vue({
        el: "#php-serial",
        data: {
            modal: {
//                show: true,
                title: '',
                content: '',
            },
            serial_content: '',
            cdict_content: '',
            phpserial_content: '',
        },
        watch: {
            serial_content: {
                handler: function () {
                    this.toCdict();
                    this.toPhpserial();
                },
                deep: true
            }
        },
        methods: {
            toCdict: function () {
                var that = this;
                $.ajax({
                    type: "POST",
                    url: "/admin/phpserial/toCdict",
                    dataType: "json",
                    data: {"content": that.serial_content},
                    success: function (result) {
                        that.cdict_content = result.msg;
                    }
                });
            },
            toPhpserial: function () {
                var that = this;
                $.ajax({
                    type: "POST",
                    url: "/admin/phpserial/toPhpserial",
                    dataType: "json",
                    data: {"content": that.serial_content},
                    success: function (result) {
                        that.phpserial_content = result.status ? (result.msg ? JSON.stringify(result.msg, null, '\t') : '') : 'SerialDefine.h 格式错误';//格式化json数据
                    }
                });
            },
            download: function (file) {
                let content = (file == 'CDict.php') ? this.cdict_content : this.phpserial_content;
                if('' != content){
                    return downloadFile(file, content);
                }else{
                    return false;
                }
            },
            mouseenter: function (file) {
                if ('cls' == file) {
//                    this.modal.show = true;
                    this.modal.title = '';
                    this.modal.content = '';
                    $("#php-serial-modal").hide();
                } else {
                    if('' != this[file]){
//                        this.modal.show = !this.modal.show;
                        this.modal.title = file;
                        this.modal.content = this[file];
                        $("#php-serial-modal").modal();
                    }
                }
            }
        },
    });
//    console.log(vm._data);
</script>