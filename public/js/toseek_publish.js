(function($){
	// -- start--搜索区文字隐藏和显示效果的实现
	$.fn.extend({
		input_toggle: function(default_value) {
			var _this = $(this);
			_this.focus(function() {
				if (_this.attr('value') == default_value) {
					_this.attr('value', '').css('color', '#000')
				}
			}).blur(function() {
				if (_this.attr('value') == '') {
					_this.attr("value", default_value).css('color', '#aaa')
				}
			});
		}
	});
	// -- end---搜索区文字隐藏和显示效果的实现	
})(jQuery)

$(function(){
	var cus_lable_val = $('.cus-lable-p input').attr('value'); // 取得当前搜索区的默认value
	$('.cus-lable-p input').input_toggle(cus_lable_val); // 调用相应函数并传递参数
	$('.book-des-text-1').bind('keydown',{MAXLENGTH:30},checkWord);
	$('.book-des-text-1').bind('keyup',{MAXLENGTH:30},checkWord);
	$('.book-des-text-2').bind('keydown',{MAXLENGTH:100},checkWord);
	$('.book-des-text-2').bind('keyup',{MAXLENGTH:100},checkWord);
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
	var bookname=false,content=false, dateChec=false,
		press=false,ori_price=false,go_price=false,tel=false,
		file_val=false,xueyuan=false,cus_label=true,password = false, log_name=false;
	//捐赠检验
	var donatename = false,q_content, d_content;
	//捐赠检验
	var address = false,doneename=false;

	var bookname_reg = new RegExp(/.{1,20}$/);
	var address_reg = new RegExp(/.{1,30}$/);
	var tel_reg = new RegExp(/^(13[0-9]|15[^4]|18[0-9]|0551)\d{8}$/);
	var price_reg = new RegExp(/^\d{1,3}\.?\d{0,2}$/);
	var xueyuan_reg = new RegExp(/^[0-9]+?$/);
	var data_reg = new RegExp(/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/);
	var lable_reg = new RegExp(/^[a-zA-Z0-9\u4E00-\u9FFF]{1,10}\s*?[a-zA-Z0-9\u4E00-\u9FFF]{0,10}\s*?[a-zA-Z0-9\u4E00-\u9FFF]{0,10}\s*?[a-zA-Z0-9\u4E00-\u9FFF]{0,10}$/);
	var  log_name_reg = new RegExp(/.{1,20}$/);
	var  password_reg = new RegExp(/.{1,20}$/);
	$('#press').bind('blur',{condition:'press',text:"出版社",reg_text:"出版社必须由不多于20个字符组成",regExp:bookname_reg},regCheck);
	$('#donatename').bind('blur',{condition:'donatename',text:"捐赠者姓名",reg_text:"捐赠者姓名必须由不多于20个字符组成",regExp:bookname_reg},regCheck);
	$('#doneename').bind('blur',{condition:'doneename',text:"获赠者姓名",reg_text:"获赠者姓名必须由不多于20个字符组成",regExp:bookname_reg},regCheck);
	$('#bookname').bind('blur',{condition:'bookname',text:"书名",reg_text:"书名必须由不多于20个字符组成",regExp:bookname_reg},regCheck);
	$('#address').bind('blur',{condition:'address',text:"地址",reg_text:"地址必须由不多于30个字符组成",regExp:address_reg},regCheck);
	// $('#original-price').bind('blur',{condition:'ori_price',text:"原价",reg_text:"原价须是小于1000整数或保留一位小数",regExp:price_reg,aimat:false},regCheck);
	// $('#going-price').bind('blur',{condition:'go_price',text:"现价",reg_text:"现价须是小于1000整数或保留一位小数",regExp:price_reg,aimat:false},regCheck);
	$('#tel').bind('blur',{condition:'tel',text:"手机号码",reg_text:"手机号码须由13/15/18/0551开头的11位或者12位数字组成",regExp:tel_reg},regCheck)
	$('.for-xueyuan').bind('blur',{condition:'xueyuan',text:"适用学院",reg_text:"请选择适用学院",regExp:xueyuan_reg},regCheck)
	$('#password').bind('blur',{condition:'password',text:"密码",reg_text:"密码必须由不多于20个字符组成",regExp:password_reg},regCheck)
	$('#user').bind('blur',{condition:'log_name',text:"用户名",reg_text:"用户名必须由不多于20个字符组成",regExp:log_name_reg},regCheck)
	// $('#expiration-date').bind('blur',{condition:'dateChec',text:"日期",reg_text:"日期不能为空"},regCheck)
	// 检测格式

	function regCheck(event){ // 格式检查
		var reg = eval('('+event.data.regExp+')');
		var _this=$(this)
		var val = $(this).val().trim();
		var _target = _this.closest('p').find("i");
		var con = event.data.condition;
		var aimat = (typeof event.data.aimat=="undefined")?true:false;
		var need_val = (typeof event.data.need_val=="undefined")?'':need_val;
		if (need_val == val) {
			if (aimat) {
				_this.error_aimat(10);
			};
			_target.html(event.data.text+"不能为空");
			if (con=="press") press=false;
			if (con=="bookname") bookname=false;
			if (con=="ori_price") ori_price=false;
			// if (con=="go_price") go_price=false;
			if (con=="tel") tel=false;
			if (con=="xueyuan") xueyuan=false;
			if (con=="address") address=false;
			if (con=="doneename") doneename=false;
			if (con=="donatename") donatename=false;
			if (con=="password") password=false;
			if (con=="log_name") log_name=false;
			// if (con=="dateChec") dateChec=false;
		}else if(!reg.test(val)){
			if (aimat) {
				_this.error_aimat(10);
			};
			_target.html(event.data.reg_text);
			if (con=="press") press=false;
			if (con=="bookname") bookname=false;
			if (con=="ori_price") ori_price=false;
			if (con=="address") address=false;
			if (con=="doneename") doneename=false;
			// if (con=="go_price") go_price=false;
			// if (con=="dateChec") dateChec=false;
			if (con=="xueyuan") xueyuan=false;
			if (con=="tel") tel=false;
			if (con=="donatename") donatename=false;
			if (con=="password") password=false;
			if (con=="log_name") log_name=false;
		}else{
			_target.html('<img src="/images/right-icon.png" align="absmiddle" />');
			if (con=="press") press=true;
			if (con=="bookname") bookname=true;
			if (con=="ori_price") ori_price=true;
			if (con=="address") address=true;
			if (con=="doneename") doneename=true;
			// if (con=="go_price") go_price=true;
			if (con=="xueyuan") xueyuan=true;
			// if (con=="dateChec") dateChec=true;
			if (con=="tel") tel=true;
			if (con=="donatename") donatename=true;
			if (con=="password") password=true;
			if (con=="log_name") log_name=true;
		}
	}
	$('#going-price').bind('blur',function(){
		var _this = $(this);
		var _i =_this.siblings("i")
		var going_price_val = $.trim($('#going-price').val());
		var ori_price_val = $.trim($('#original-price').val());
		if (''==going_price_val) {
			_i.html('现价不能为空');
			go_price=false;
		}else if(parseInt(going_price_val)==0||parseInt(going_price_val)>999.99||!price_reg.test(going_price_val)){
			_i.html('1<原价<999.99 (至多保留两位小数)');
			go_price=false;
		}else if(''!=ori_price_val&&(parseInt(ori_price_val)<parseInt(going_price_val))) {
			_i.html('现价不能大于原价');
			if ($('#original-price').siblings("i").text()=="原价不能小于现价") {
				$('#original-price').siblings("i").html('');
			};
			go_price=false;
		}else{
			if (''!=ori_price_val&&!(parseInt(ori_price_val)==0||parseInt(ori_price_val)>999.99||!price_reg.test(ori_price_val))&&!(parseInt(ori_price_val)<parseInt(going_price_val))) {
				ori_price=true;
				$('#original-price').siblings("i").html('原价 <img style="margin-top:0px; vertical-align:top;" src="/images/right-icon.png" align="absmiddle" />');
			};
			_i.html('现价 <img src="/images/right-icon.png" style="margin-top:0px; vertical-align:top;" align="absmiddle" />');
			go_price=true;

//			alert(ori_price+" "+go_price)
		}
	})
	$('#original-price').bind('blur',function(){
		var _this = $(this);
		var _i =_this.siblings("i");
		var ori_price_val = $.trim($('#original-price').val());
		var going_price_val = $.trim($('#going-price').val());
		if (''==ori_price_val) {
			_i.html('原价不能为空');
			ori_price=false;
		}else if(parseInt(ori_price_val)==0||parseInt(ori_price_val)>999.99||!price_reg.test(ori_price_val)){
			_i.html('1<现价<999.99 (至多保留两位小数)');
			ori_price=false;
		}else if(''!=going_price_val&&(parseInt(ori_price_val)<parseInt(going_price_val))) {
			// alert('l')
		//			alert(ori_price_val+going_price_val)
			// alert(boolean(going_price_val))
			_i.html('原价不能小于现价');
			if ($('#going-price').siblings("i").text()=="现价不能大于原价") {
				$('#going-price').siblings("i").html('');
			};
			ori_price=false;
		}else{
			if (''!=going_price_val&&!(parseInt(going_price_val)==0||parseInt(going_price_val)>999.99||!price_reg.test(going_price_val))&&!(parseInt(ori_price_val)<parseInt(going_price_val))) {
				go_price=true;
				$('#going-price').siblings("i").html('现价 <img style="margin-top:0px;vertical-align:top;" src="/images/right-icon.png" align="absmiddle" />');
			};
			_i.html('原价 <img src="/images/right-icon.png" style="margin-top:0px;vertical-align:top;" align="absmiddle" />');
			ori_price=true;
//			alert(ori_price+" "+go_price)
		}

	})


	$('.cus-label').bind('blur',function(){ // 自定义标签格式检查
		var _this = $(this);
		var val = $.trim(_this.val());
		var _i =_this.closest('p').find("i");
		if (val!=cus_lable_val&&''!=val) {
			// alert('dao')
			if (!lable_reg.test(val)) {
				cus_label=false;
				_this.error_aimat(10);
				_i.html('每个标签由少于10个字母or数字or汉字构成，最多定义四个标签并以空格分隔');
			}else{
				cus_label=true;
				_i.html('<img src="/images/right-icon.png" align="absmiddle" />');
			}
		}else{
			cus_label=true;
			_i.html('');
		}
	})
	$('#book-des-text').blur(function(){ // 喊话内容格式检查
		var _wrap = $('.textarea-wrap');
		if ($.trim($(this).val())==='') {
			_wrap.siblings("i").html("喊话内容不能为空").end().error_aimat(10);
			content=false;
		}else{
			_wrap.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			content=true;
		}
	})
	$(".book-des-text-2").each(function(){
		$(this).blur(function(){ // 喊话内容格式检查
			var _wrap = $(this).closest('.textarea-wrap');
			if ($.trim($(this).val())==='') {
				_wrap.siblings("i").html("喊话内容不能为空").end().error_aimat(10);
				if ($(this).attr('data-sort')==1) {
					d_content = false
				}else{
					q_content = false
				}
			}else{
				_wrap.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
				if ($(this).attr('data-sort')==1) {
					d_content = true;
				}else{
					q_content = true;
				}
			}
		})
	})

	$('#expiration-date').focus(function(){
		$(this).closest('p').find('i').html('');
	})
	$('#validation_code_2').live('focus',{target:".val-code"},valCodeCheck)

	// 买书提交
	$('.seek-sumbit-btn').click(function(){
		if (!(content&&bookname&tel&cus_label&$('#validation_code_2').data('yeah')==1)) {
			$('#book-des-text').add('#bookname').add('#tel').add('.cus-label').blur();
			return false;
		}else{
			getLabels(); // 将标签付给隐藏的input框
			// alert($('.get-lables').val()) // 输出隐藏框的内容
			return true; // 调用系统提交事件
		}
	})
	// 卖书提交
	$('.publish-sumbit-btn').click(function(){
		var _date = $('#expiration-date');
		var date = _date.val();
		if ((date!="")) {
			_date.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			dateChec=true;
		}else{
			_date.siblings("i").html('日期不能为空');
			dateChec=false;
		}
		if ($('.update-file').hasClass('succ-update')) {
			file_val=true;
		}else{
			file_val=false;
		}
		if(!(content&$('#validation_code_2').data('yeah')==1&bookname&press&ori_price&go_price&tel&dateChec&xueyuan&cus_label&file_val)){
		//	alert("content&valCode&bookname&press&ori_price&go_price&tel&dateChec&xueyuan&cus_label"+content+valCode+bookname+press+ori_price+go_price+tel+dateChec+xueyuan+cus_label)
			$('#book-des-text').add('#press').add('#tel')
				.add('#bookname').add('.for-xueyuan').add('.cus-label').blur();
			return false;
		}else{
			getLabels(); // 将标签付给隐藏的input框
			// alert($('.update-file').val())
			return true;
		}
	})
	// 捐赠提交
	$('.donate-sumbit-btn').click(function(){
		if ($('.update-file').hasClass('succ-update')) {
			file_val=true;
		}else{
			file_val=false;
		}
		// alert('dd')
		if(!(xueyuan&donatename&q_content&d_content&$('#validation_code_2').data('yeah')==1&bookname&file_val)){
			$('#donatename').add('#bookname').add(".book-des-text-2").add('.for-xueyuan').blur();
			return false;
		}else{
			// console.log('yes');
			// alert($('.update-file').val())
			return true;
		}
	})
	// 获赠提交
	$('.feedback-sumbit-btn').click(function(){
		var _date = $('#expiration-date');
		var date = _date.val();
		if ((date!="")) {
			_date.siblings("i").html('<img src="/images/right-icon.png" align="absmiddle" />');
			dateChec=true;
		}else{
			_date.siblings("i").html('日期不能为空');
			dateChec=false;
		}
		if(!(address&dateChec&doneename&content&$('#validation_code_2').data('yeah')==1)){
			$('#doneename').add('#address').add(".book-des-text-2").blur();
			// alert("address&dateChec&donatename&content&"+address+dateChec+doneename+content)
			return false;
		}else{
			// alert($('.update-file').val())
			return true;
		}
	})
	//捐赠登陆
	$('.donate-log-btn ').click(function(){
		if(!(password&log_name&$('#validation_code_2').data('yeah')==1)){
			$('#password').add('#user').blur();
			return false;
		}else{
			// console.log("yes")
			return true;
		}
	})
	// 增加漂流信息
	$('.todrifting-sumbit-btn').click(function(){
		if ($('.update-file').hasClass('succ-update')) {
			file_val=true;
		}else{
			file_val=false;
		}
		if(!(donatename&content&$('#validation_code_2').data('yeah')==1&bookname&file_val)){
			$('#donatename').add('#bookname').add(".book-des-text-2").blur();
			// console.log(donatename);
			// console.log(content);
			// console.log(bookname);
			// console.log(file_val);
			// console.log();
			
			return false;
		}else{
			console.log('yes');
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
	arr_cusLabel = $('.cus-label').val()!="您还可以自定义标签，以空格分割"?$('.cus-label').val():'';
	gived_label = arrLabel.join(' ');
	var all_label = gived_label+' '+arr_cusLabel;
	$('.get-lables').val(all_label);
}
