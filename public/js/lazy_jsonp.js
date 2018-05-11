/**
 * Created by Administrator on 2018/5/10 0010.
 * 模拟jsonp跨域    https://www.cnblogs.com/naokr/p/6603936.html
 * jsonp原理：  jsonp主要是通过script可以链接远程url来实现跨域请求的。如： <script src="http://xxx.com/?callback=xxxx"></script>
 * 其中 callback 参数定义了一个函数名，远程服务端接收到该参数并把原本要返回的json数据包裹在 callback 指定的函数 xxxx 中返回给客户端所动态创建的 script 标签处。
 */
var JSONP = {
    // 获取当前时间戳
    now: function () {
        return (new Date()).getTime();
    },

    // 获取16位随机数
    rand: function () {
        return Math.random().toString().substr(2);
    },

    // 删除节点元素
    removeElem: function (elem) {
        var parent = elem.parentNode;
        if (parent && parent.nodeType !== 11) {
            parent.removeChild(elem);
        }
    },

    // url组装
    parseData: function (data) {
        var ret = "";
        if (typeof data === "string") {
            ret = data;
        }
        else if (typeof data === "object") {
            for (var key in data) {
                ret += "&" + key + "=" + encodeURIComponent(data[key]);
            }
        }
        // 加个时间戳，防止缓存
        ret += "&_time=" + this.now();
        ret = ret.substr(1);
        return ret;
    },

    getJSON: function (url, data, func) {
        // 函数名称
        // 如果未定义函数名的话随机成一个函数名
        // 随机生成的函数名通过时间戳拼16位随机数的方式，重名的概率基本为0
        // 如:jsonp_1355750852040_8260732076596469
        var jsonpfunc = "jsonp_" + this.now() + '_' + this.rand();
        data.callback = data.callback ||  "callback";
        var name = data.callback;
        data[name] = jsonpfunc;
        delete data.callback;


        // 拼装url
        url = url + (url.indexOf("?") === -1 ? "?" : "&") + this.parseData(data);


        // 创建一个script元素
        var script = document.createElement("script");
        script.type = "text/javascript";
        // 设置要远程的url
        script.src = url;
        // 设置id，为了后面可以删除这个元素
        script.id = "id_" + jsonpfunc;

        // 把传进来的函数重新组装，并把它设置为全局函数，远程就是调用这个函数
        window[jsonpfunc] = function (json) {
            // 执行这个函数后，要销毁这个函数
            window[jsonpfunc] = undefined;
            // 获取这个script的元素
            var elem = document.getElementById("id_" + jsonpfunc);
            // 删除head里面插入的script，这三步都是为了不影响污染整个DOM啊
            JSONP.removeElem(elem);
            // 执行传入的的函数
            func(json);
        };

        // 在head里面插入script元素
        var head = document.getElementsByTagName("head");
        if (head && head[0]) {
            console.log(head);
            console.log(script);
            head[0].appendChild(script);
        }
    }
};

//调用方式
// callbackData = {}; //json格式数据
// callbackData = JSON.parse(callbackData);
// callbackData.callback = 'jsonp_callback';
// JSONP.getJSON(url, callbackData, function (result) {
//     console.log(result);
// });