$(function(){
	$(window).scroll(function(){
		t = $(document).scrollTop();
		var _this = $('.sort-nav');
		if(t >=324){
			_this.css({position:"absolute",top:document.body.scrollTop-324,left:0,marginTop:0});
		}else{
			_this.css({position:"absolute",top:0,left:0,marginTop:''});
		}
	})
})