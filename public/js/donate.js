$(function () {
	var timer = 600;
	var list = $('.don_list').find('ul');
	var index = 0;
	var len = list.find('li').length;
	if (len<6) {  // 如果输出的条目少于6条时
				for (var i = 5; i >= len; i--) {  // 隐藏多余的按钮
					$(".change_ppt").find("li").eq(i).css("display","none");
				};
			};
	$(".change_ppt").find("li").each(function(){  // 切换
		$(this).click(function(){
			index = parseInt($(this).html())-1;
			var cur_index = parseInt($(".change_ppt").find(".current").html())-1;
			if (cur_index!=index){
				console.log(index);
				list.animate({"left":-990*(index)},timer);
				$(".change_ppt").find("li").removeClass("current");
				$(this).addClass("current");
				// inde
			}
			else{
				alert("the same");
			}
		})
	})
	$('.next').add(".prev").click(function(){
		if ($(this).hasClass('next')) {
			index++;
		}else{
			index--;
		}
		if (index==len||index==-1) {
			var chageGroup = $('.change_group');
			var href ="/donate/index/page/";
			var cur_index_group =  parseInt(chageGroup.attr("data-group"),10);  // 获取当前所在组
			if (index==len) {
				index=len-1;
				chageGroup.html('跳转到下一组');
				chageGroup.attr("href",href+(cur_index_group+1)); // 跳到下一页
				pop_todonate();
			}else if(index==-1) {
				if (cur_index_group!=1) {
					console.log(chageGroup.data("href"))
					chageGroup.html('跳转到上一组');
					chageGroup.attr("href",href+(cur_index_group-1));// 跳到上一页
					pop_todonate();
				}else{  // 第一组信息
					alert("这已经是第一组信息了");
				}
				index=0;
			};
		}else{
			$(".change_ppt").find("li").removeClass("current").eq(index).addClass("current");
			// $(this).addClass("current");
			list.animate({"left":-990*index},timer);
		}	
	})
	$('.look_again').click(function(){
		if (list.css('left')!=0) {
			list.animate({"left":0},timer*2);
		};
		index = 0;
		$(".change_ppt").find("li").removeClass("current").eq(index).addClass("current");
		$('.close').click();
	})
	// 调出pop
	var bool = false;
	$('.to_donate').click(function(){
		if (!bool) {
			_this = $(this);
			// if ($(".title-h1-text").attr("data-sort")!=1) {
				$(".title-h1-text").html("捐赠书籍信息登记");
				$('.select_btn')[0].style.display="none";
				$('.todonate-form')[0].style.display="block";
			// };
			$('.bd')[0].style.display="block";
			$('.select-wrap')[0].style.display="block";
			bool = true;
		};
	})
	$('.close').click(function(){
		$(".todonate-form").find("i").html('');
		$('#todonate_name').add("#tel").add("#bookname").add("#validation_code").val('');
		$('.todonate-form')[0].style.display="none";
		$('.select_btn')[0].style.display="none";
		$('.bd')[0].style.display="none";
		$('.select-wrap')[0].style.display="none";
		bool = false;
	})
	// 提交捐赠
	var todonate_name = false, tel=false, book_name=false;
	var userNmae, bookName, colValue, telVal;
	var tel_reg = new RegExp(/^(13[0-9]|15[^4]|18[0-9]|0551)\d{8}$/);
	var bookname_reg = new RegExp(/.{1,20}$/);
	$('#todonate_name').bind('blur',function(){
		var _this = $(this);
		userNmae = $.trim(_this.val());
		if (userNmae=='') {
			_this.siblings("i").html("姓名不能为空").end().error_aimat(10);
			todonate_name=false;
		}else{
			_this.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			todonate_name=true;
		}
	});
	$('#tel').bind('blur',function(){
		var _this = $(this);
	 	telVal = $.trim(_this.val());
		if (telVal=='') {
			_this.siblings("i").html("手机号码不能为空").end().error_aimat(10);
			tel=false;
		}else if(!tel_reg.test(telVal)){
			_this.siblings("i").html("格式不准确").end().error_aimat(10);
		}else{
			_this.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			tel=true;
		}
	})
	$('#bookname').bind('blur',function(){
		var _this = $(this);
		bookName = $.trim(_this.val());
		if (bookName=='') {
			_this.siblings("i").html("书名不能为空").end().error_aimat(10);
			book_name=false;
		}else if(!bookname_reg.test(bookName)){
			_this.siblings("i").html("格式不准确").end().error_aimat(10);
		}else{
			_this.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			book_name=true;
		}
	})
	colValue = $('.for-xueyuan option:selected').val();
	$('#validation_code').live('focus',{target:".val-code"},valCodeCheck)
	// 借阅提交
	$('.todonate-sumbit-btn').click(function(){
		if (!(todonate_name&tel&book_name&$('#validation_code').data('yeah')==1)) {
			$('#todonate_name').add('#tel').add('#bookname').blur();
			return false;
		}else{
			$.ajax({
			   type: "POST",
			   url: "/donate/idonatechk",
			   data: {
			   		uName : userNmae,
			   		bName : bookName,
			   		col : colValue,
			   		tel : telVal
			   	},
			   success: function(data){
			   		if (data == "suc") {
						$('.close').click();
						alert("捐赠成功，志愿者将会尽快联系您");
			   		}else{
			   			alert("操作失败");//失败的操作
			   		}
			   }
			});

			return false; // 调用系统提交事件
		}
	})

})
function pop_todonate(){
	$(".title-h1-text").html("本组捐赠信息已浏览完毕");
	$('.select_btn')[0].style.display="block";
	$('.bd')[0].style.display="block";
	$('.select-wrap')[0].style.display="block";
}