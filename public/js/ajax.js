//Ajax 控制

  //   window.onload = function() {
   // 	 sendHeader();
    // }
//    
    /*
     *   载入头部函数
     */
    var http_request;
   function sendHeader() {
    	//alert("函数触发！");
    	if (window.ActiveXObject) {
    		http_request=new ActiveXObject("Microsoft.XMLHTTP");
    	} else{
    		http_request=new XMLHttpRequest();
    	};

    	if (http_request) {
            var time=Date();
    		var url="/global/li";
    		http_request.open("post", url, true);
    		http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            var data="change";
            http_request.onreadystatechange=sendHeadAction;
            http_request.send(data);
    	};
    }
    
    function sendHeadAction() {
    	if (http_request.readyState==4) {
    		if (http_request.status==200) {
    			var res=http_request.responseText;
    			document.getElementById('li').innerHTML=res;
    		};
    	};
    }


    function creatCode() {
    	document.getElementById('code').src = '/global/vcode?rand='+ Math.random()*1000;
    }
    
    //输出求书推荐书籍
    //$(document).ready(function(){
    //    $('#bookname').blur(function(){
    //        var val = $(this).val();
    //        buyRelative(val);
    //    })
    //})
    
    function buyRelative(d) {
    	//alert(d);
    	if (window.ActiveXObject) {
    		http_request=new ActiveXObject("Microsoft.XMLHTTP");
    	} else{
    		http_request=new XMLHttpRequest();
    	};

    	if (http_request) {
            var time=Date();
    		var url="/seek/relative";
    		http_request.open("post", url, true);
    		http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            var data="buybook="+d;
            http_request.onreadystatechange=buyRelativeAction;
            http_request.send(data);
    	};
    }
    
    //处理相关书籍推荐
    function buyRelativeAction() {
    	if (http_request.readyState==4) {
    		if (http_request.status==200) {
    			var res=http_request.responseText;
    			document.getElementById('spotlight-list').innerHTML=res;
    		};
    	};
    }
    
    
    //分页控制
    var http_request;
    //@param p 页数
    //@param pc 跳转方式
    function sendPage (p,pc) {
    	// 创建Ajax引擎
    	//alert(p);
    	//alert(pc);
    	if (window.ActiveXObject) {
    		http_request=new ActiveXObject("Microsoft.XMLHTTP");
    	} else{
    		http_request=new XMLHttpRequest();
    	};

    	if (http_request) {
            var time=Date();
    		var url="/person/dopage";
    		http_request.open("post", url, true);
    		http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            var data="page="+p+"&type="+pc+"&date="+time;
            http_request.onreadystatechange=PageChuli;
            http_request.send(data);
    	};
    }

    // 定义处理函数
    function PageChuli () {
    	if (http_request.readyState==4) {
    		if (http_request.status==200) {
    			var res=http_request.responseText;
    			document.getElementById('right-main').innerHTML=res;
    		};
    	};
    }
    
    ///个人中心跳转处理
    function msgcheck(c) {
    	//alert(d);
    	if (window.ActiveXObject) {
    		http_request=new ActiveXObject("Microsoft.XMLHTTP");
    	} else{
    		http_request=new XMLHttpRequest();
    	};

    	if (http_request) {
            var time=Date();
    		var url="/person/msgcheck";
    		http_request.open("post", url, true);
    		http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            var data="msgcontent="+c;
            http_request.onreadystatechange=centerAction;
            http_request.send(data);
    	};
    }
    
    ///个人中心跳转处理
    function center(d) {
    	//alert(d);
    	if (window.ActiveXObject) {
    		http_request=new ActiveXObject("Microsoft.XMLHTTP");
    	} else{
    		http_request=new XMLHttpRequest();
    	};

    	if (http_request) {
            var time=Date();
    		var url="/person/index";
    		http_request.open("post", url, true);
    		http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            var data="change="+d;
            http_request.onreadystatechange=centerAction;
            http_request.send(data);
    	};
    }
    
    //处理个人中心跳转
    function centerAction() {
    	if (http_request.readyState==4) {
    		if (http_request.status==200) {
    			var res=http_request.responseText;
    			document.getElementById('right-main').innerHTML=res;
    		};
    	};
    }
