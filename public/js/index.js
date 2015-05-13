(function($){
	$.fn.Floatdiv=function(i){
		var _this=$(this);
		var parse_wid=parseInt($(this).parent().width());
		var parse_hei=parseInt($(this).parent().height());
		_this.css({padding:'0px 4px'})
		var settings = {};
		settings.index=i;
		switch(i){
			case 0:
			case 1:
			case 2:
				settings.height='33px';
				settings.lineHeight='33px';
				settings.xPos=40*(i+1);
				settings.yPos=40*i;
				(i==0||i==2)?settings.ySpeed=-0.7+i*0.1:settings.ySpeed=0.4;
				$(this).css({"font-size":"22px",height:"33px","line-height":"33px",'font-weight':"700",color:"#333","z-index":666})
				break;
			case 3:
			case 4:
			case 5:
				settings.height='24px';
				settings.lineHeight='24px';
				settings.xPos=(i-3)*60+30;
				settings.yPos=30+(i-2)*35;
				(i==4)?settings.ySpeed=-0.9+i*0.1:settings.ySpeed=0.5+i*0.1;
				$(this).css({"font-size":'16px',height:"24px","line-height":"24px",color:'#777',"z-index":333})
				break;
			case 6:
			case 7:
			case 8:
			case 9:
				settings.height='21px';
				settings.lineHeight='21px';
				settings.xPos=(i-6)*40+10;
				settings.yPos=30+(i-5)*20;	
				(i==6||i==8)?settings.ySpeed=-1.2+i*0.1:settings.ySpeed=0.1+i*0.1;
				$(this).css({"font-size":'14px',height:"21px","line-height":"21px",color:'#aaa',"z-index":222})
				break;
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
				settings.height='18px';
				settings.lineHeight='18px';
				settings.xPos=(i-10)*35;
				settings.yPos=(i-9)*30;	
				(i==11||i==16)?settings.ySpeed=-2.0+i*0.1:settings.ySpeed=-0.3+i*0.1;
				settings.ySpeed=2-i*0.1;
				$(this).css({"font-size":'12px',height:"18px","line-height":"18px",color:'#ccc',"z-index":111})
				break;
			default:
				break
		}
		settings.hRange=parse_hei-parseInt(settings.height);
		function floatAuto(){
			if(settings.yPos>settings.hRange||settings.yPos<i-10) settings.ySpeed= -settings.ySpeed; //比较图片是否到达边界，如查到达边界 
			if (settings.index==0||settings.index==3||settings.index==4||settings.index==5) {
				settings.yPos+=settings.ySpeed;
			}else{
				settings.yPos-=settings.ySpeed;
			}
 			_this.css("top",settings.yPos+"px");
 			_this.css("left",settings.xPos+"px");
		};
		var setInter=setInterval(floatAuto,35);
		$(this).hover(function(){
			$(this).addClass('main-bgCol-fontFam main-transition')
			$(this).css({'text-decoration':'none','z-index':9999,color:'#fff','border-radius':'3px','box-shadow':'0 0 3px 3px #e96845'});
			clearInterval(setInter);
		},function(){
			$(this).removeClass('main-bgCol-fontFam main-transition');
			switch(i){
				case 0:
				case 1:
				case 2:
					$(this)[0].style.color="#333";
					$(this)[0].style.zIndex="666";
					break;
				case 3:
				case 4:
				case 5:
					$(this)[0].style.color="#777";
					$(this)[0].style.zIndex="333";
					break;
				case 6:
				case 7:
				case 8:
				case 9:
					$(this)[0].style.color="#aaa";
					$(this)[0].style.zIndex="222";
					break;
				case 10:
				case 11:
				case 12:
				case 13:
				case 14:
					$(this)[0].style.color="#ccc";
					$(this)[0].style.zIndex="111";
					break;
				default:
					break;
			}
			$(this).css({background:'','box-shadow':''})
			setInter=setInterval(floatAuto,35);
		})
	}
	$.fn.FS_carousel=function(settings){
			var $focus = $(this),
				$picUl = $focus.find('ul'),  // ul
				$liList = $picUl.children('li'); // li
			var n=$liList.length, 
				dir=settings.direction,
				prop,
				prop=dir==='left'?'width':'height', // 
				txtH=parseInt(settings.txtHeight);  // 标题的高度
			// alert(dir)
			settings.width = parseInt($focus.width());   // 盒子的宽度
			settings.height = parseInt($focus.height());
			// 设置ul包裹区的高度或宽度
			prop==='height'?($picUl.height(settings.height*n+"px")):($picUl.width(settings.width*n+"px"));
			// var liList_hei = settings.height-txtH;
			// alert($focus.height())
			$liList.each(function(){ // 给每一个li指定高度宽度，防止走形
				$(this).css({width:settings.width,height:settings.height});
			});
			// 文本包裹区
			var txtBox = $('<ul id="txt-wrap" style="height:'+txtH+'px;line-height:'+txtH+'px;border-top:1px solid #ccc"></ul>');
			var txtList = '';
			// 通过图片的alt属性获取需要的标题
			for (var i = n - 1; i >= 0; i--) {
				$liList.eq(n-i-1).find('a').attr('index',n-i-1);
				txtList+='<li class=\'normal\' style="width:'+(settings.width-n-1)/n+1+'px">'+ $liList.eq(n-i-1).find('img').attr('alt') +"</li>"
			};
			// $focus.wrap('<div id="FS-carousel"></div>')
			txtBox.append(txtList);
			$focus.append(txtBox);
			// $('#FS-carousel').css({height:settings.height+txtH+1,border:'1px solid #ccc','margin-bottom':'26px'});
			$('.txt-li').eq(n-1).css('border','none');
			// $('.txt-li').on('click',function(){alert('l')})
			var txt_wrap=$('#txt-wrap');
			txt_wrap.find('li:first').removeClass("normal").addClass('current').end().find('li:last').css('border-right','none');
	}
	// -- start---最近求书动态动画
	$.fn.spotLight=function(option){
		if (!option) var option = {};
		var pixel = option.pixel,
			speed = option.speed,
			timer = option.timer,
			_this = $(this);
		var spotlightID = setInterval('moreSpotlight()', timer);
		_this.hover(function() {
			clearInterval(spotlightID);
		}, function() {
			spotlightID = setInterval('moreSpotlight()', timer);
		});
		moreSpotlight = function() {
			_this.animate({
				'margin-left': pixel
			}, speed, function() {
				$(this).find('li:first').appendTo($(this));
				$(this).removeAttr("style");
			})
		}	
	};
	// -- end---最近求书动态动画
})(jQuery)
$(function(){
	$('#FS-carousel').FS_carousel({direction:top,txtHeight:28}); // 执行
	$('.float-text-inner a').each(function(i){
		$(this).Floatdiv(i);
	});
	// tab 选项卡的切换
	$('#myTab li').children('a').each(function(){
		$(this).click(function(){
			var target=$(this).attr('href');
			var bool=$(this).parent('li').hasClass('active');
			if (!bool) {
				$(this).parent('li').siblings().removeClass('active').addClass('no-active').end().addClass('active').removeClass('no-active');
				$(target).siblings().removeClass('active').end().addClass('active');

			}
			return false;
		})
	})
	// 挥泪割爱
	$('.book-list-item').each(function(){
		$(this).hover(function(){
			$(this).find('.book-list-info').stop().animate({'bottom':'0px'},100);
		},function(){
			$(this).find('.book-list-info').stop().animate({'bottom':'-50px'},200);
		})
	})
	// 挥泪割爱动画调用---start
	var show_pic_width = parseInt($('.publish-list-ul li').width(),10)+20; // 获取书籍宽度
	$('.publish-list-ul').spotLight({
		pixel: -show_pic_width,
		speed: 1500,
		timer: 5600
	}); //调用函数并传参

	$('.next-step').each(function(i){ // 帮助页面
		$(this).live('click',function(){
			// alert(i)
			if (i==3) {
				$('.bg-cover')[0].style.display='none';
			};
			$('.step-no')[i].style.display='none';
			$('.step-no')[i+1].style.display='block';
		})
	})
	// 漂流信息切换页面
	var timer = 600;
	var list = $('.drift-wrap').find('ul');
	$('.next').add(".prev").click(function(){
		if ($(this).hasClass('next')) {
			list.find("li:last").prependTo(".drift-list");
			list.css("margin-left",-148);
			list.stop().animate({"margin-left":0},timer);
			// index++;
		}else{
			list.stop().animate({"margin-left":-148},timer,function(){
				list.css("margin-left",0);
				list.find("li:first").appendTo(".drift-list");
			});
		}
	})
	$('#help').live('click',function(){ // 帮助
		// 将方便带给大家
		// 将公益进行到底
		// 我是易书网
		// 也是“益”书网
		var cssText = '"<style type="text/css">.introduce_text{z-index:10000;font-size:70px;position:absolute;top:200px;left:393px;color:#ec7150;line-height:50px;*line-height:80px;font-family:"微软雅黑";font-weight:bolder;}.close-help{z-index:10000;font-size:20px;position:fixed;top:30px;right:50px;padding:4px 6px;color:#f5f5f5;border-radius:3px;display:block}.close-help:hover{color:#fff;text-decoration:none;}.bg-cover2{position: fixed;top: 0;left: 0px;height: 100%;width: 100%;background-color: #000;opacity: 0.60;filter:alpha(opacity=60);z-index:9990;-webkit-animation-name: opacity;-webkit-animation-duration: 0.3s;-webkit-animation-timing-function: ease-in-out;;}.step-no1,.step-no2,.step-no3,.step-no4,.step-no{text-align: left;display: none;}.next-step{cursor: pointer;}.next-step:hover{width: 75px;height: 30px;}.step-no1 img,.step-no2 img,.step-no3 img,.step-no4 img, .step-no img{position: absolute;z-index: 10000;}</style>"'
		var bg_cover ='<div class="bg-cover2"></div>';
		// var step_tpl = '"<a href="javascript:void(0)" class="close-help main-bgCol-fontFam">取消</a><div class="step-no" style="display:block"><div class="introduce_text">将方便带给大家</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">将公益进行到底</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">&nbsp;&nbsp;&nbsp;我是易书网</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">也是“益”书网</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><img src="/images/help/step1-01.png" style="left:153px;top:113px;"><img src="/images/help/line.png" style="left:300px;top:116px;"><img src="/images/help/step1-03.png" style="left:442px;top:125px;"><img src="/images/help/nextstep.png" style="left:620px;top:200px;" class="next-step"></div><div class="step-no"><img src="/images/help/step4-01.png" style="left:870px;top:178px;"><img src="/images/help/line.png" style="left:735px;top:186px;"><img src="/images/help/step4-02.png" style="left:482px;top:165px;"><img src="/images/help/nextstep.png" style="left:600px;top:300px;" class="next-step"></div><div class="step-no"><img src="/images/help/step2-01.png" style="left:143px;top:513px;"><img src="/images/help/line.png" style="left:375px;top:540px;"><img src="/images/help/step2-02.png" style="left:512px;top:545px;"><img src="/images/help/nextstep.png" style="left:620px;top:640px;" class="next-step"></div><div class="step-no"><img src="/images/help/step3-01.png" style="left:153px;top:953px;"><img src="/images/help/line.png" style="left:390px;top:986px;"><img src="/images/help/step3-02.png" style="left:532px;top:990px;"><img src="/images/help/nextstep.png" style="left:620px;top:1060px;" class="next-step"></div>"';
		var step_tpl = '"<a href="javascript:void(0)" class="close-help main-bgCol-fontFam">取消</a><div class="step-no" style="display:block"><div class="introduce_text">将方便带给大家</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">将公益进行到底</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">&nbsp;&nbsp;&nbsp;我是易书网</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">也是“益”书网</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step">"';
		var bg = $(bg_cover);
		var tpl = $(step_tpl);
		var css = $(cssText);
		if ($('.step-no').data('created')!=1) {
			$("body").append($(bg_cover));
			$("body").append($(tpl));
			$("head").append($(css));
			$('.step-no').data('created','1');
		}else{
			$('.close-help')[0].style.display='block';
			$('.bg-cover2')[0].style.display='block';
			$('.step-no')[0].style.display='block';
		}
		$('.next-step').each(function(i){
			$(this).live('click',function(){
				switch(i){
					case 1:
					case 2:
						break;
					case 3:
						$('.bg-cover2')[0].style.display='none';
						$('.close-help')[0].style.display='none';
						$("html,body").animate({scrollTop:0}, 500); 
						break;
					// case 5:
					// 	$("html,body").animate({scrollTop:513}, 500); 
					// 	break;
					// case 6:
					// 	$("html,body").animate({scrollTop:953}, 500); 
					// 	break;
					// case 7:
					// 	$('.bg-cover2')[0].style.display='none';
					// 	$('.close-help')[0].style.display='none';
					// 	$("html,body").animate({scrollTop:0}, 500); 
					// 	break;
				}
				$('.step-no')[i].style.display='none';
				$('.step-no')[i+1].style.display='block';
			})
		})
		$('.close-help').live('click',function(){
			$('.bg-cover2')[0].style.display='none';
			$('.step-no').css('display','none');
			$(this)[0].style.display='none';
			$("html,body").animate({scrollTop:0}, 500); 
		})
		$('#help').live('click',function(){
			$('.close-help')[0].style.display='block';
			$('.bg-cover2')[0].style.display='block';
			$('.step-no')[0].style.display='block';
		});
	})
})


// 检查用户是否看过帮助弹出框

get_cookie_value = getCookie('hadguide');
if (!get_cookie_value) {
	setCookie();
	showHelper();
};

function showHelper() {
	console.log("help!");
	var cssText = '"<style type="text/css">.introduce_text{z-index:10000;font-size:70px;position:absolute;top:200px;left:393px;color:#ec7150;line-height:50px;*line-height:80px;font-family:"微软雅黑";font-weight:bolder;}.close-help{z-index:10000;font-size:20px;position:fixed;top:30px;right:50px;padding:4px 6px;color:#f5f5f5;border-radius:3px;display:block}.close-help:hover{color:#fff;text-decoration:none;}.bg-cover2{position: fixed;top: 0;left: 0px;height: 100%;width: 100%;background-color: #000;opacity: 0.60;filter:alpha(opacity=60);z-index:9990;-webkit-animation-name: opacity;-webkit-animation-duration: 0.3s;-webkit-animation-timing-function: ease-in-out;;}.step-no1,.step-no2,.step-no3,.step-no4,.step-no{text-align: left;display: none;}.next-step{cursor: pointer;}.next-step:hover{width: 75px;height: 30px;}.step-no1 img,.step-no2 img,.step-no3 img,.step-no4 img, .step-no img{position: absolute;z-index: 10000;}</style>"'
		var bg_cover ='<div class="bg-cover2"></div>';
		var step_tpl = '"<a href="javascript:void(0)" class="close-help main-bgCol-fontFam">取消</a><div class="step-no" style="display:block"><div class="introduce_text">将方便带给大家</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">将公益进行到底</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">&nbsp;&nbsp;&nbsp;我是易书网</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step"></div><div class="step-no"><div class="introduce_text">也是“益”书网</div><img src="/images/help/nextstep.png" style="left:615px;top:320px;" class="next-step">"';
		var bg = $(bg_cover);
		var tpl = $(step_tpl);
		var css = $(cssText);
		if ($('.step-no').data('created')!=1) {
			$("body").append($(bg_cover));
			$("body").append($(tpl));
			$("head").append($(css));
			$('.step-no').data('created','1');
		}else{
			$('.close-help')[0].style.display='block';
			$('.bg-cover2')[0].style.display='block';
			$('.step-no')[0].style.display='block';
		}
		$('.next-step').each(function(i){
			$(this).live('click',function(){
				switch(i){
					case 1:
					case 2:
						break;
					case 3:
						$('.bg-cover2')[0].style.display='none';
						$('.close-help')[0].style.display='none';
						$("html,body").animate({scrollTop:0}, 500); 
						break;
				}
				$('.step-no')[i].style.display='none';
				$('.step-no')[i+1].style.display='block';
			})
		})
		$('.close-help').live('click',function(){
			$('.bg-cover2')[0].style.display='none';
			$('.step-no').css('display','none');
			$(this)[0].style.display='none';
			$("html,body").animate({scrollTop:0}, 500); 
		})
		$('#help').live('click',function(){
			$('.close-help')[0].style.display='block';
			$('.bg-cover2')[0].style.display='block';
			$('.step-no')[0].style.display='block';
		});
}

function setCookie() {
	var d = new Date();
	d.setTime(d.getTime() + (7*24*60*60*1000));
	var expires = "expires=" + d.toGMTString();
	document.cookie = 'hadguide=1;' + expires; 
}

function getCookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1) {
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if (c_end != -1) {
				c_end = document.cookie.length
			};
			return unescape(document.cookie.substring(c_start, c_end));
		};
	};
	return "";
}
