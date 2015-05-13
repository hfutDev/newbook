(function($){
	// -- start--搜索区文字隐藏和显示效果的实现
	$.fn.extend({
		input_toggle: function(default_value) {
			$(this).focus(function() {
				if ($(this).attr('value') == default_value) {
					$(this).attr('value', '');
					$(this).css('color', '#000')
				}
			}).blur(function() {
				if ($(this).attr('value') == '') {
					$(this).attr("value", default_value);
					$(this).css('color', '#aaa')
				}
			});
		}
	});
	// -- end---搜索区文字隐藏和显示效果的实现	
})(jQuery)

$(function(){
	var val = $('.cus-lable-p input').attr('value'); // 取得当前搜索区的默认value
	$('.cus-lable-p input').input_toggle(val); // 调用相应函数并传递参数
	$('.book-des-text-1').bind('keydown',{MAXLENGTH:30},checkWord);
	$('.book-des-text-1').bind('keydown',{MAXLENGTH:30},checkWord);
	$('.book-des-text-2').bind('keydown',{MAXLENGTH:100},checkWord);
	$('.book-des-text-2').bind('keydown',{MAXLENGTH:100},checkWord);
	$('.wordCheck').click(function(){
		$('#book-des-text').focus();
	})
	$('.label-box-ul li').each(function(i){ // 自定义标签
		var _this = $(this);
		var label_val = _this.find('span').text();
		_this.toggle(function(i){
			_this.addClass("selected");
			_this.append('<strong>已选中</strong>');
		},function(i){
			$(this).removeClass("selected");
			$(this).find('strong').remove();
		})
	})
	// 格式检验
	var bookname=false,date=false,content=false,valCode=false, dateChec=false,press=false,ori_price=false,go_price=false,tel=false,file_val
file_val=false,xueyuan=false;;
	var bookname_reg = new RegExp(/^[a-zA-Z0-9_\u4E00-\u9FFF]{1,20}$/);
	var tel_reg = new RegExp(/^(13[0-9]|15[^4]|18[2|6|7|8|9])\d{8}$/);
	var price_reg = new RegExp(/^[0-9]{1,3}\.?[0-9]?$/);
	var data_reg = new RegExp(/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/);
	$('#press').bind('blur',{condition:'press',text:"出版社",reg_text:"出版社必须由不多于20个非字母字符组成",regExp:bookname_reg},regCheck);
	$('#bookname').bind('blur',{condition:'bookname',text:"书名",reg_text:"书名必须由不多于20个非字母字符组成",regExp:bookname_reg},regCheck);
	$('#original-price').bind('blur',{condition:'ori_price',text:"原价",reg_text:"原价须是小于1000整数或保留一位小数",regExp:price_reg,aimat:false},regCheck);
	$('#going-price').bind('blur',{condition:'go_price',text:"现价",reg_text:"现价须是小于1000整数或保留一位小数",regExp:price_reg,aimat:false},regCheck);
	$('#tel').bind('blur',{condition:'tel',text:"手机号码",reg_text:"手机号码须由11位数字组成",regExp:tel_reg},regCheck)
	$('.for-xueyuan').bind('blur',{need_val:'--请选择--',condition:'date',text:"适用学院",reg_text:"请选择适用学院",regExp:data_reg},regCheck)
	// $('.update-file').bind('blur',{condition:'file_val',text:"书籍图片",reg_text:"请上传书籍图片",aimat:false},regCheck)
	// 检测格式
	function regCheck(event){
		var reg = eval('('+event.data.regExp+')');
		var _this=$(this)
		var val = $(this).val().trim();
		var _target = _this.closest('p').find("i");
		var con = event.data.condition;
		var aimat = (typeof event.data.aimat=="undefined")?true:false;
		var need_val = (typeof event.data.need_val=="undefined")?'':need_val;
		if (need_val == val) {
			if (aimat) {
				_this.error_aimat(110);
			};
			_target.html(event.data.text+"不能为空");
			if (con=="press") press=false;
			if (con=="bookname") bookname=false;
			if (con=="ori_price") ori_price=false;
			if (con=="go_price") go_price=false;
			if (con=="tel") tel=false;
			if (con=="need_val") xueyuan=false;
			if (con=="date") date=false;
		}else if(!reg.test(val)){
			if (aimat) {
				_this.error_aimat(110);
			};
			_target.html(event.data.reg_text);
			if (con=="press") press=false;
			if (con=="bookname") bookname=false;
			if (con=="ori_price") ori_price=false;
			if (con=="go_price") go_price=false;
			if (con=="date") date=false;
			if (con=="need_val") xueyuan=false;
			if (con=="tel") tel=false;
			// if (con=="file_val") file_val=false;
		}else{
			_target.html('<img src="/images/right-icon.png" align="absmiddle" />');
			if (con=="press") press=true;
			if (con=="bookname") bookname=true;
			if (con=="ori_price") ori_price=true;
			if (con=="go_price") go_price=true;
			if (con=="need_val") xueyuan=true;
			if (con=="date") date=true;
			// if (con=="file_val") file_val=true;
			if (con=="tel") tel=true;
		}
	}
	$('#book-des-text').blur(function(){
		if (parseInt($('.wordChange').text()) == 30) {
			$('.textarea-wrap').error_aimat(10);
			$('.textarea-wrap').siblings("i").html("喊话内容不能为空");
			content=false;
		}else{
			$('.textarea-wrap').siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			content=true;
		}
	})
	// 验证码检验
	$('#validation_code_2').blur(function(){
		var $this=$(this)
		var val = $this.val();
		if ('' == val || val.length!=4) {
			$this.error_aimat(10);
			valCode=false;
		}else{
			valCode=true;
			$.ajax({
				type:"POST",
				url:"5555",
				data:{
					valCode: $this.val()
				},
				success: function(data){
					if (1==data) {
						$this.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
						valCode=true;
					}else{
						$this.siblings("i").html("验证码错误");
						valCode=false;
					}
				}
			})
		}
	})
	// 买书提交
	$('.seek-sumbit-btn').click(function(){
		if (!(content&valCode&&bookname)) {
			$('#book-des-text').blur();
			$('#bookname').blur();
			$('#validation_code_2').blur();
			return false;
		}else{
			getLabels(); // 将标签付给隐藏的input框
			// alert($('.get-lables').val()) // 输出隐藏框的内容
			return true; // 调用系统提交事件
		}
	})
	// 卖书提交
	$('.publish-sumbit-btn').click(function(){
		var date = $('#expiration-date').val();
		if ((date!="")) {
			$('#expiration-date').siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			dateChec=true;
		}else{
			$('#expiration-date').siblings("i").html('日期不能为空');
			dateChec=false;
		}
		if ($('.update-file').val()!='') {
			file_val=true;
		}else{
			$('.update-file').closest('p').find("i").html('请上传书籍图片');
		}
		// if ($('.for-xueyuan').val()!='--请选择--') {
		// 	xueyuan=true;
		// }else{
		// 	$('.for-xueyuan').closest('p').find("i").html('请选择适用学院');
		// }
		if(!(content&valCode&bookname&press&ori_price&go_price&tel&dateChec&file_val&xueyuan)){
			$('#book-des-text').blur();
			$('#press').blur();
			$('#original-price').blur();
			$('#going-price').blur();
			$('#tel').blur();
			$('#bookname').blur();
			$('.for-xueyuan').blur();
			$('#validation_code_2').blur();
			// alert($('.update-file').val()+'kk')

			return false;
		}else{
			getLabels(); // 将标签付给隐藏的input框
			// alert($('.update-file').val())
			return true;
		}
	})
})
function getLabels(){
	var arrLabel = new Array();　//储存标签
	var arr_cusLabel,gived_label='';
	$('.label-box-ul li').each(function(){ // 取得可选的标签
		var val = $(this).find('span').text();
		if ($(this).hasClass("selected")) {
			arrLabel.push(val)
		};
	})
	arr_cusLabel = $('.cus-label').val()?$('.cus-label').val():'';
	gived_label = arrLabel.join(' ');
	var all_label = gived_label+' '+arr_cusLabel;
	$('.get-lables').val(all_label);
}
function checkWord(event){
	var str = $(this).val();
	cur_len = getStrLen(str,event);
	if (cur_len > (event.data.MAXLENGTH)*2) {
		$(this).val( str.substring(0, cur_len-1));
	}else{
		$(this).siblings('.wordCheck').find('.wordChange').html(Math.floor((event.data.MAXLENGTH*2 - cur_len)/2));
	}
}
function getStrLen(str,event){
	cur_len = 0;
	i = 0
	for (; (i < str.length)&&(cur_len<=event.data.MAXLENGTH*2); i++) {
		if (str.charCodeAt(i)> 0 && str.charCodeAt(i) < 128) {
			cur_len ++;
		}else{
			cur_len +=2;
		}
	};
	return cur_len;
};
