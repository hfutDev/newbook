$(function () {
	// var href = $('.P-bookPic-wrap').find("a").attr("href").split("/");
	// var bookID = href[4]; //获取书籍
	$('.grid').each(function(){
		var _this = $(this);
		var timer = 300;
		var crosBox = _this.find('.crossing_history');
		$(this).hover(function(){
			var hei = _this.height()+parseInt(_this.css("margin-top"))+parseInt(_this.css("margin-bottom"));
			crosBox.height(hei-20);
			crosBox.css("display","block");
			var inner_box = _this.find('.dynamic');
			var crosBox_ul = inner_box.find('.dynamic_list');
			var crosBox_ul_hei = crosBox_ul.find("li").length*40 + 20;
			// console.log(hei + "  " + crosBox_ul_hei);
			if (hei - crosBox_ul_hei <= 82) {
				crosBox_ul.css({"overflow-y":"scroll","height":(hei-80)});
			}else{
				var marginTop = (hei - crosBox_ul_hei)/2 - 20; // 剧中
				var marginTop2 = (hei - 152)/2; // 剧中
				inner_box.css("margin-top",marginTop);
				$(".yuyue").css("margin-top",marginTop2);
			}
			var index = _this.parent().index() // 当前index
			if ((index+1)%4 == 0) { // 最后一列防止溢出
				crosBox.css("right","228px");
			}else{
				crosBox.css("left","228px");
			}
			crosBox.stop().animate({width:"250px"},timer);
		},function(){
			crosBox.stop().animate({width:"0"},timer);
			// crosBox.delay(timer).css("display","none");
		})
	})
	var bor_name = false, tel=false;
	var tel_reg = new RegExp(/^(13[0-9]|15[^4]|18[0-9]|0551)\d{8}$/);
	$('#bor_name').bind('blur',function(){
		var _this = $(this);
		if ($.trim(_this.val())=='') {
			_this.siblings("i").html("姓名不能为空").end().error_aimat(10);
			bor_name=false;
		}else{
			_this.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			bor_name=true;
		}
	});
	$('#tel').bind('blur',function(){
		var _this = $(this);
		var val = $.trim(_this.val());
		if (val=='') {
			_this.siblings("i").html("手机号码不能为空").end().error_aimat(10);
			tel=false;
		}else if(!tel_reg.test(val)){
			_this.siblings("i").html("格式不准确").end().error_aimat(10);
		}else{
			_this.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			tel=true;
		}
	})
	$('#validation_code').live('focus',{target:".val-code"},valCodeCheck)
	// 借阅提交
	var bookID = 0;
	$('.bro-sumbit-btn').add('.yuyue-sumbit-btn').click(function(){
		if (!(bor_name&tel&$('#validation_code').data('yeah')==1)) {
			$('#bor_name').add('#tel').blur();
			return false;
		}else{
			var data_type,data_name,data_tel;
			data_name = $("#bor_name").val();
			data_tel=$("#tel").val();
			// 1：表示借阅 2：表示预约
			book_type = $('.title-h1-text').data("type");
			$.ajax({
			   type: "POST",
			   url: "/drift/todriftchk",
			   data: {
			   		type:book_type,
			   		name:data_name,
			   		tel:data_tel,
			   		bookID:bookID
			   	},
			   success: function(data){
			   		if (data == "suc") {
						$('.close').click();
						alert("操作成功");
			   		}else{
			   			alert("操作失败");//失败的操作
			   		}
			   }
			});
			return false; // 调用系统提交事件
		}
	})
	// 调出pop
	var bool = false;
	$('.bor-btn').add('.yuyue-btn').click(function(){
		if (!bool) {
			bookID = $(this).attr("data-bookid"); // 获取booid
			_this = $(this);
			if (_this.hasClass('bor-btn')) {
				$('.title-h1-text').data("type",1);
				$('.title-h1-text').html("借阅\""+ $(".P-bookName").html()+"\"");
			}else{
				$('.title-h1-text').data("type",2);
				$('.title-h1-text').html("预约\""+ $(".P-bookName").html()+"\"");
			}
			var bID = _this.attr('data-bookID');
			$('.bro-sumbit-btn').attr('data-bookID',bID);
			$('.bd')[0].style.display="block";
			$('.bro-form-wrap')[0].style.display="block";
			$('#bor_name').focus();
			bool = true;
		};
	})
	$('.close').click(function(){
		if (bool) {
			$(".bro-form").find("i").html('');
			$('#bor_name').add("#tel").add("#validation_code").val('');
			$('.bd')[0].style.display="none";
			$('.bro-form-wrap')[0].style.display="none";
			bool = false;
		};
	})
	// $('.select_condi li').each(function(i){ // 条件筛选
	// 	var _this = $(this);
	// 	var label_val = _this.find('span').text();
	// 	_this.toggle(function(i){
	// 		_this.addClass("selected");
	// 		_this.append('<strong>已选中</strong>');
	// 	},function(i){
	// 		$(this).removeClass("selected");
	// 		$(this).find('strong').remove();
	// 	})
	// })
})