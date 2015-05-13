
// 自定义hashtable -- start--
function HashTable(){
	this._hash = {};
	this._count = 0;
	//添加或更新key
	this.put = function(key, value){
		if (this._hash.hasOwnProperty(key)) {
			this._hash[key] = value;
			return true;
		}else{
			this._hash[key] = value;
			this._count++;
			return false;
		}
	}
	//获取元素个数
	  	this.size = function(){
		return this._count;
	}
	//获取key指定的值
	  	this.get = function(key){
		if (this.contiansKey(key)) {
			return this._hash[key];
		};
	}
	//检查是否包含指定的key
	  	this.contiansKey = function(key){
			return this._hash.hasOwnProperty(key);
	}
	//清空所有的key
	  	this.clear = function(){
		this._hash = {};
		this._count = 0;
	}
}// 自定义hashtable -- end --

// 按顺序声明所有的表情title
var em_phrase = ['[微笑]','[撇嘴]','[色]','[发呆]','[得意]','[流泪]','[害羞]','[闭嘴]','[睡觉]','[大哭]','[尴尬]','[发怒]','[调皮]','[龇牙]','[惊讶]','[难过]','[酷]','[冷汗]','[抓狂]','[吐]','[偷笑]','[可爱]','[白眼]','[傲慢]','[饥饿]','[困]','[惊恐]','[流汗]','[憨笑]','[大兵]','[奋斗]','[咒骂]','[疑问]','[嘘..]','[晕]','[折磨]','[衰]','[骷髅]','[敲打]','[再见]','[擦汗]','[抠鼻]','[鼓掌]','[糗大了]','[坏笑]','[左哼哼]','[右哼哼]','[哈欠]','[鄙视]','[委屈]','[快哭了]','[阴险]','[亲亲]','[吓]','[可怜]','[菜刀]','[西瓜]','[啤酒]','[篮球]','[乒乓]','[咖啡]','[米饭]','[猪头]','[玫瑰]','[凋谢]','[示爱]'];
var oEmotionsHt = new HashTable(); // 定义一个HashTable对象
$(function(){
	for (var i = 0; i < em_phrase.length; i++) {
		oEmotionsHt.put(em_phrase[i],i); // 调用put函数，将图片的title作为表的key，i作为value即图片的src
	};
	// document.write(oEmotionsHt.get('[11]'))
});

// 解析表情
function AnalyticEmotion(s) {
	//alert('l')
	if (typeof(s) != 'undefined') {
		var sArr = s.match(/\[.*?\]/g);
		if (sArr==null) {  // 无匹配项是直接返回
			return s;
		}
		for (var i = 0; i < sArr.length; i++) {
			if (oEmotionsHt.contiansKey(sArr[i])) {
				var reStr = "<img src=\"/images/emotion/" + oEmotionsHt.get(sArr[i]) + ".gif\" height=\"24\" width=\"24\" />";
				s = s.replace(sArr[i], reStr);
			};
		};
		
	};
	return s;
}
(function($){
	var target = null;
	$.fn.Emotion = function(strTarget){
		$(this).live('click',function(event){
			var _this=$(this)
			$('head').append('<link rel="stylesheet" type="text/css" href="/css/Emotion.css">')
			event.stopPropagation();
			target = eval('('+strTarget+')')
			// target = _this.closest('.sumbit_wrapper').siblings('.content_edt');
			if (!$('#emotions').length) { // 先判断是否存在，防止多次append
				$('body').append('<div id="emotions"></div>');
			};
			
			$('#emotions').html('<div>正在载入，请稍候...</div>');
			$('#emotions').click(function(event){
				event.stopPropagation();
			});
			var arrow = '<div class="em_arrow"><em class=\'em_arrow_em\'>◆</em></div>';
			$('#emotions').html(arrow+ '<div class="container"></div>')
			showEmotions(); // 调用加载表情函数
			var eTop = _this.offset().top;
			var eLeft = _this.offset().left -343/2;
			if (eTop+220>parseInt($('body').height())) {
				$('#emotions').css({
					top: eTop-188-25,
					left: eLeft
				})
				$('.em_arrow_em').removeAttr('style');
				$('.em_arrow_em').css({
					color:'#bbb',
					position: 'absolute',
					bottom:'-19px',
					left:164
				})
			}else{
				$('#emotions').css({
					top: eTop+ _this.height(),
					left: eLeft
				});
				$('.em_arrow_em').css({
					color:'#bbb',
					position: 'absolute',
					top:'-17px',
					left:164
				})
			}
		})
	
		$('body').click(function(){
			$('#emotions').remove();
		});
		// 将表情的title写入文本框中
		$.fn.insertText = function(text){
			this.each(function(){
				if (this.tagName !== 'INPUT' && this.tagName!== 'TEXTAREA') {
					return;
				};
				if (document.selection) {
					this.focus();
					var cr = document.selection.createRange();
					cr.text = text;
					cr.collapse();
					cr.select();
				}else if (this.selectionStart || this.selectionStart=='0') { // 在鼠标选中处输入
					var start = this.selectionStart;
					var end   =	this.selectionEnd;
					this.value = this.value.substring(0,start) + text + this.value.substring(end, this.value.length);
					this.selectionStart = this.selectionEnd = start + text.length;
				}else{
					this.value += text;
				}
			});
			return this;
		}
		// 加载表情
		function showEmotions () {
			$('#emotions .container').html('');
			var emotions_set = [];
			for (var i = 0; i < oEmotionsHt.size(); i++) {
				emotions_set += '<a href="javascript:void(0);" title="' + em_phrase[i] + '"><img src="/images/emotion/' + i + '.gif" alt="' + em_phrase[i] + ' "wdith="22" height="22" /></a>';
				// $('#emotions .container').append($('<a href="javascript:void(0);" title="' + em_phrase[i] + '"><img src="../Views/images/emotion/' + i + '.gif" alt="' + em_phrase[i] + ' "wdith="22" height="22" /></a>'));
			};
			$('#emotions .container').html(emotions_set); // 
			$('#emotions .container a').click(function(){
				target.insertText($(this).attr('title'));
				$('#emotions').remove();
			})
		}
	} // Emotion end
})(jQuery);


