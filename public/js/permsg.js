(function($){
	$.fn.textarea_hidden = function (){ // 隐藏textarea
		$(this).css({'height':'20px','border-bottom':'','outline':'','border-bottom-right-radius': '','border-bottom-left-radius': ''})
			.removeClass('content_focus').siblings('.sumbit_wrapper').hide('fast').siblings('.wordCheck-msg').hide();
	}
	$.fn.textarea_disp = function(){ //显示textarea
		var _this =$(this);
		_this.css({'height':'40px','border-bottom':'none','outline':'none','border-top-right-radius': '3px','border-top-left-radius': '3px'})
			.addClass('content_focus').focus().siblings('.sumbit_wrapper').fadeIn('slow').siblings('.wordCheck-msg').show();
	}
	$.fn.msg_wrap_dis= function(){  // 创建回复框
		var _this = $(this);
		var cssStyle = '"<style type="text/css">.reply_msg{text-indent: 0;}.content_edt{box-shadow: inset 0 2px 3px #ddd5bf;-webkit-box-shadow:inset 0 2px 3px #ddd5bf;-moz-box-shadow:inset 0 2px 3px #ddd5bf;padding: 2px 4px 0;font-size: 14px;word-wrap: break-word;line-height: 18px;height: 40px;color: #000;background: #fff;text-align: left;width: 422px;/**width:424px;*/resize: none;font-family: Tahoma, 宋体;float: left;outline: none;border-radius: 3px 3px 0 0;}.feed_arrow_em{color: #e9dfd5;position: absolute;top: -10px;right: 5px;}.feed_arrow_span{color: #f9f5ea;position: absolute;top: -8px;right: 5px;}.wordCheck-msg{margin: 0 1.5px 5px;background: #fff;width: 427px;*width:430px;color: #ec7150;border-top: none;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;text-align: right;height: 25px;line-height: 25px;cursor: text;float: left;}.sumbit_wrapper{height: 20px;position: relative;}.smile_btn{background: url(/images/face_iocn.png) no-repeat;background-position: 0px 0px;width: 20px;height: 20px;display: inline-block;cursor: pointer;float: left;margin-left: 7px;margin-top: 3px;}.smile_btn:hover{background-position: -25px 0px;}.sumbit_btn{cursor: pointer;float: right;font-size: 13px;line-height: 20px;background: #ffa00a;width: 40px;border-radius: 2px;color: #fff;}.sumbit_btn:hover{color: #fff;text-decoration: none;background: #ff8820;}#exp_tb{position:absolute;top:30px;left:-120px;z-index:602;padding:8px; display:block;background: #ccc;border-radius: 5px;}#exp_tb td{height: 25px;width: 25px;cursor: pointer;}#arrow{position:absolute;top:12px;left:9px; z-index:601;opacity:0.7;width: 0px;height: 0px;border: 5px solid transparent;border-bottom: 13px solid #ccc;}#exp_tb font{font-size:11px; padding:3px 8px; padding-bottom:5px;cursor: pointer;}</style>"'
		$('head').append($(cssStyle));
		msg_wrap = _this.closest('.message_item_inner').siblings('.message_item_comment_wrapper');
		msg_wrap[0].style.display="block"
		var arrow = $('<div class="feed_arrow"><em class=\'feed_arrow_em\'>◆</em><span class=\'feed_arrow_span\'>◆</span></div>');
		msg_wrap.append(arrow);
		msg_wrap.append('<form class="comment_input" action="" method="post" name="comment-form" id="comment-form"></form><div class="clear"></div>');
		// msg_wrap.append('"<form action="post" name="GM-msg"><div class="textarea-wrap comment_input"><textarea id="book-des-text" class=\'content_edt\'></textarea><span class="wordCheck-msg">您还可输入:&nbsp;&nbsp;<strong class="wordChange">0</strong>/100</span></div><p class="face-sumbit-btn"><span title="添加表情" class="smile_btn"></span><a href="javascript:void(0)" class="sumbit_btn">留言</a></p></form>"');
		msg_wrap.find('.comment_input').html('<textarea name="comment-text" id="content_edit" class=\'content_edt\' ></textarea><div class=\'wordCheck-msg\'><span style="font-family: Georgia; font-size: 26px;"class="wordChange" >140</span>/140</div><div class="clear"></div><div id=\'comment_smile_sumbit\' class=\'sumbit_wrapper\'><span class=\'smile_btn\'></span><input type="button" name="submit-btn" value="评论" class=\'sumbit_btn\'></div>')
		_this.off('click');  // 解除绑定事件
		_this[0].style.cursor='default';
		$('.content_edt').focus(content_focus); // 输入框变化
		$('.smile_btn').Emotion('$(this).closest(".sumbit_wrapper").siblings(".content_edt")');  // 启用表情
		$('.content_edt').bind('keydown',{MAXLENGTH:140},checkWord);
		$('.content_edt').bind('keyup',{MAXLENGTH:140},checkWord);
		$('.wordCheck-msg').click(function(){
			$('.content_focus').focus();
		}) 
		_this.closest('.message_item_content').find('.content_edt').addClass('content_focus').focus();
		$('.sumbit_btn').on('click',reply_msg); // 测试本地解析,传输数据
	}
})(jQuery)
$(function(){
	var msg_wrap = null;
	$('body').on('click','.reply_btn',function(){
			event.stopPropagation();
			_this = $(this);
			var cont_edt = _this.closest('.message_item_content').find('.content_edt');
			if (cont_edt.length) { // 已经生成过
				// alert('l')
				return false;
			}else if(!cont_edt.length){ // 为生成 建立回复框
				textarea_change(_this,'_this.msg_wrap_dis()');
			}
	});//each -- end
	$('.contorl_btn').live('click',function(){
		var _this = $(this);
		var text = _this.text();
		if (text!='已取消') {
			var r=confirm('你确认'+text+'？');
			if (r) {
				$.ajax({
					type:'POST',
					url:'/person/cancelbook',
					data:{
						deal_id:_this.attr('id'),
						deal_type: _this.closest('tr').attr('class')
					},
					success:function(data){
						
						if(data==1){//交易结束
							alert("取消成功");
							_this.closest('tr').find('.dealing_state_going').html('已完成');
							_this.html("已取消");
							_this[0].style.cursor='default';
						} else {
							alert("取消失败");
						}
					}
				})
			}else{
				return false;
			}
		}else{
			alert('该交易已经取消');
		}
	}) // end of ajax

})
function content_focus(){ // 输入框focus时的变化
	var _this = $(this);
		if (!_this.hasClass('content_focus')) { // 页面上只能有一个输入框有content_focus类
			textarea_change(_this,'_this.textarea_disp()');
		}else{	
			return true;
	}
}
function textarea_change(_this,strFun){
	var bool = false;
	$('.content_edt').each(function(){
		if(''!=$(this).val()){
			bool = true;
			return;
		}
	})
	if (bool) {
		var r = confirm('你确定要放弃正在编辑的内容吗？');
		// alert($('.content_edt').val())
		if (r) {
			$('.content_edt').textarea_hidden();
			$('.content_edt').each(function(){
				$(this).val('')
			})
			eval(strFun);
			$('.wordChange').html(140)
		}else{
		 	_this.blur();
		 	$('.content_focus').focus();
		 	// _this.unbind(focus)
		}
	}else{
		$('.content_edt').textarea_hidden();
		eval(strFun);
	}
}
function reply_msg(){ // 提交消息
	// event.preventDefault();
	var _this = $(this)
	var val = $.trim($('.content_focus').val());
	var msgId = $(this).closest('.message_item_content').find('.floor_count').attr('id');
			if (val=='') {
				alert('至少说点神马吧');
				$('.content_focus').focus();
				return false;
			}else{
				$.ajax({
					type:"POST",
					url:'/person/replycheck',
					data:{
						value: val,id: msgId
					},
					success:function(data){
						//alert(data)
						if(typeof data=="string"){
							var data = eval("("+data+")");

							var item_wrap = _this.closest('.message_item_content');
							var tpl = '"<dl class="message_item_comment reply_msg_wrap"><dt class="message_item_avatar reply_msg_ava"><div class="message_item_img_wrapper"><a href=""><img src=""></a></div></dt><dd class="message_item_content reply_msg_inner"><div class="message_item_inner"><p class="message_content reply_msg"></p><div><span style="margin-left:0px;" class="message_item_time"></span><a href="javascript:void(0)" class="visitor_name user_name"></a></div></div></dd></dl>"'
							_this.closest('.message_item_comment_wrapper').replaceWith(tpl);
							item_wrap.children('.reply_msg_wrap').eq(0).find('.message_item_time').html(data.reply_time) // 写入留言时间
								.end().find('.reply_msg').html(AnalyticEmotion(data.reply_content)) // 写入留言回复
								.end().find('.message_item_img_wrapper img').attr('src',data.src)//写入留言头像
								.end().find('.message_item_img_wrapper a').attr('href',data.href)//写入个人中心地址
								.end().find('.user_name').html(data.user_name);
							return false;
						}
					}
				}) // ajax end
			}
} //reply_msg --end