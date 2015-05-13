$(function(){
	$('#book-des-text').bind('keydown',{MAXLENGTH:100},checkWord);
	$('#book-des-text').bind('keyup',{MAXLENGTH:100},checkWord);

	// var valCode=false;
	$('#validation_code_3').live('focus',{target:"p"},valCodeCheck);
	 keyDown('13',submit)
	$('.smile_btn_2').Emotion('$("#book-des-text")'); // 启用表情
	$('.msg-submit').live('click',submit);
	$('.wordCheck').live('click',function(){
		$('#book-des-text').focus();
	})
	function submit(){
		var val = $.trim($("#book-des-text").val());
		if (''==val) {
			alert('至少说点神马吧');
			$(this).focus();
			return false;
		}else if($('#validation_code_3').data('yeah')!=1){
			$('#validation_code_3').error_aimat(10);
		}else if($('#validation_code_3').data('yeah')==1){
			$.ajax({
				type:"POST",
				url:'/person/msgcheck',
				data:{
					value: val
				},
				success:function(data){
					if(typeof data=="string"){
						alert('留言成功！');
						$('#book-des-text').val('');
						$('#validation_code_3').val('');
						$('#validation_code_3').closest('p').find('i').html('');
						$('html,body').animate({scrollTop:200},500);
						center('gmsg');
					} else {
						alert('留言失败！');
					}
				}
			})
		}else{
			$('#validation_code_3').blur();
		}
	}
})