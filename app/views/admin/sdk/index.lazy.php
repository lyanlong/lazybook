<style type="text/css">
    .select2-container .select2-selection--single{
        height:40px;
        line-height: 40px;
        padding-right: 5px;
    }
</style>

<div id="tool-sdk-form" class="tool-sub-frame">
    <div class="row form-row">
        <div class="col-3"><label for="domain">域名地址</label></div>
        <div class="col-3">
            <select class="custom-select d-block w-100" id="domain" required>
                <?php foreach ($data['domain'] as $key => $doma): ?>
                    <option value="<?= $key ?>"><?= $key . ' | ' . $doma ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-1">
            <select class="custom-select d-block w-100" id="method" required>
                <option value="GET">GET</option>
                <option value="POST">POST</option>
            </select>
        </div>
        <div class="col-3">
            <select class="custom-select d-block w-100" id="contentType" required>
                <option value="x-www-form-urlencoded">x-www-form-urlencoded(http默认类型)</option>
                <option value="json">json</option>
            </select>
        </div>
    </div>
    <div class="row form-row">
        <div class="col-3"><label for="apiType">接口类型</label></div>
        <div class="col-4">
            <select class="custom-select d-block w-100" id="apiType">
<!--                <option></option>-->
                <?php foreach ($data['apiType'] as $key => $doma): ?>
                    <option value="<?= $key ?>"><?= $key . ' | ' . $doma ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-3">
            <input type="text" class="form-control" id="sdk-pay-api-extend" placeholder="不带r参支付回调(eg:xx_callback)">
        </div>
    </div>
    <div class="row form-row">
        <div class="col-3"><label for="pathType">api入口文件目录地址</label></div>
        <div class="col-7">
            <select class="custom-select d-block w-100" id="pathType" required>
                <?php foreach ($data['pathType'] as $key => $doma): ?>
                    <option value="<?= $key ?>"><?= $key . "  ($doma)"?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row form-row">
        <div class="col-3"><label for="callback-data">回调数据</label></div>
        <div class="col-7"><textarea id="callback-data" class="form-control"></textarea></div>
    </div>
    <div class="row form-row">
        <div class="col-3">
            <button class="btn" id="tool-sdk-form-built-url">生成url</button>
            <button class="btn" id="tool-sdk-form-callback">模拟执行回调</button>
        </div>
        <div class="col-7">
            <input type="text" class="form-control" id="tool-sdk-form-url" placeholder="" readonly>
        </div>
    </div>

</div>

<div class="modal fade" id="callback-response-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="callback-response--modal-title">回调结果</h4>
            </div>
            <div class="modal-body">
                <textarea id="callback-response-modal-content" style="width: 600px;height: 900px;" readonly>

                    </textarea>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#sdk-pay-api-extend").click(function(){
            $("#apiType").val('');
            return;
        });

        $("#domain").select2({

        });
        $("#tool-sdk-form-built-url").on("click", function () {
            var url;
            var domain = $("#domain").val(); //主机地址
            var method = $("#method").val(); //请求方式
            var contentType = $("#contentType").val();  //数据格式
            var apiType = $("#apiType").val(); //接口类型，即判断是sdk登录还是sdk支付回调还是游戏请求后台api
            var sdkPayApiExtend = $("#sdk-pay-api-extend").val(); //特殊接口类型，即请求不带r参的回调地址
            var pathType = $("#pathType").val(); //用于拼接脚本在服务器上的实际目录
            var callbackData = $("#callback-data").val(); //回调数据(json格式)
            url = "http://" + domain + pathType + (sdkPayApiExtend ? sdkPayApiExtend : apiType) + '.php';
            $("#tool-sdk-form-url").val(url);
        });

        $("#tool-sdk-form-callback").on("click", function () {
            var url = $("#tool-sdk-form-url").val();
            var method = $("#method").val(); //请求方式
            var contentType = $("#contentType").val();  //数据格式
            var callbackData = $("#callback-data").val(); //回调数据(json格式)
            if (!url || !method || !contentType) {
                alert("请求失败！数据不完整！！");
                return;
            }

            //ajax跨域问题解决方式之一： jsonp，注意此方法需要服务端代码做相应修改
//            $.ajax({
//                url: url,
//                type: method,
//                dataType: 'jsonp',
//                jsonp: "jsonp_callback",
//                contentType: 'application/' + contentType,
//                data: JSON.parse(callbackData),
//                success: function(result){
//                    $("#callback-response-modal-content").html(JSON.stringify(result, null, '\t'));
//                    $("#callback-response-modal").modal();
//                },
//                error: function (XMLHttpRequest, textStatus, errorThrown) {
//                    alert("error");
//                    // 状态码
//                    console.log(XMLHttpRequest.status);
//                    // 状态
//                    console.log(XMLHttpRequest.readyState);
//                    // 错误信息
//                    console.log(textStatus);
//                }
//            });

            //ajax跨域问题解决方式之二： 服务器代理即隐藏跨域，此方法只需在代理服务器上加一个调用远程服务器的脚本即可，不用修改要跨域访问的服务器端的代码
            $.ajax({
                url: '/admin/sdk/remoteHttp',
                type: 'POST',
//                dataType: 'json',
                data: {"remoteurl": url, "method": method, "contentType": contentType, "callbackData": callbackData, },
                success: function(result){
//                    $("#callback-response-modal-content").html(JSON.stringify(result, null, '\t'));
                    $("#callback-response-modal-content").html(result);
                    $("#callback-response-modal").modal();

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("error");
                    // 状态码
                    console.log(XMLHttpRequest.status);
                    // 状态
                    console.log(XMLHttpRequest.readyState);
                    // 错误信息
                    console.log(textStatus);
                }
            });



        });
    });
</script>