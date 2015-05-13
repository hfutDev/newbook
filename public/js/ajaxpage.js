//Ajax 分页控制

    var http_request;
    function sendPage (p,ps) {
       // alert(p);
    	// 创建Ajax引擎
    	if (window.ActiveXObject) {
    		http_request=new ActiveXObject("Microsoft.XMLHTTP");
    	} else{
    		http_request=new XMLHttpRequest();
    	};

    	if (http_request) {
            var time=Date();
    		var url="/seek/page";
    		http_request.open("post", url, true);
    		http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            var data="pagechange="+p+"&page="+ps+"&date="+time;
            http_request.onreadystatechange=pageChuli;
            http_request.send(data);
    	};
    }

    // 定义处理函数
    function pageChuli () {
    	if (http_request.readyState==4) {
    		if (http_request.status==200) {
    			var res=http_request.responseText;
    			document.getElementById('showdata').innerHTML=res;
    		};
    	};
    }