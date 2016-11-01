$.extend({
    getObjLen: function (obj) {
        var len = 0;
        $.each(obj,function () {
            len++;
        });
        return len;
    },
    setCookie : function(name,value)
    {
        var Days = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days*24*60*60*1000);
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString() +';path=/';
    },
    getCookie : function(name)
    {
        var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
        if(arr != null) return unescape(arr[2]); return null;
    },
    delCookie : function(name) {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = getCookie(name);
        if (cval != null) document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
    },
    regex: function(pattern){
        switch(pattern)
        {
            case 'required': pattern = /\S+/i;break;
            case 'email': pattern = /^\w+([-+.]\w+)*@\w+([-.]\w+)+$/i;break;
            case 'qq':  pattern = /^[1-9][0-9]{4,}$/i;break;
            case 'id': pattern = /^\d{15}(\d{2}[0-9x])?$/i;break;
            case 'ip': pattern = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/i;break;
            case 'zip': pattern = /^\d{6}$/i;break;
            case 'mobi': pattern = /^1[3|4|5|7|8][0-9]\d{8}$/;break;
            case 'phone': pattern = /^((\d{3,4})|\d{3,4}-)?\d{3,8}(-\d+)*$/i;break;
            case 'url': pattern = /^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))+(\/?\S*)?$/i;break;
            case 'date': pattern = /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/i;break;
            case 'datetime': pattern = /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29) (?:(?:[0-1][0-9])|(?:2[0-3])):(?:[0-5][0-9]):(?:[0-5][0-9])$/i;break;
            case 'int':	pattern = /^\d+$/i;break;
            case 'float': pattern = /^\d+\.?\d*$/i;break;
            default : pattern = null;break;
        }
        return pattern;
    },
});