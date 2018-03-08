(function(win,doc){
        var s = doc.createElement("script"), h = doc.getElementsByTagName("head")[0];
        if (!win.alimamatk_show) {
            s.charset = 'gbk';
            s.async = true;
            s.src = "http://a.alimama.cn/tkapi.js";
            s.kslite = "";
            h.insertBefore(s, h.firstChild);
        }
        var o = {
            pid: "mm_97928704_42764700_281828738",/*推广单元ID，用于区分不同的推广渠道*/
            plugins:[{name:"aroundbox"}]/*需要加载的插件：任意位置角标插件*/
        };
        win.alimamatk_onload = win.alimamatk_onload || [];
        win.alimamatk_onload.push(o);
    })(window,document);