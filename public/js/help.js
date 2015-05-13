$(function() {
	$('.submit-btn').click(function() {
		if ('' == $('#feedback-text').val()) {
			alert('至少说点神马吧');
			return false;
		} else {
			return true;
		}
	})
	$('#feedback-text').bind('keydown', {
		MAXLENGTH: 100
	}, checkWord);
	$('#feedback-text').bind('keyup', {
		MAXLENGTH: 100
	}, checkWord);
	function checkWord(event) {
		var str = $(this).val();
		cur_len = getStrLen(str, event);
		if (cur_len > (event.data.MAXLENGTH) * 2) {
			$(this).val(str.substring(0, cur_len - 1));
		} else {
			$(this).siblings('.wordCheck').find('.wordChange').html(Math.floor((event.data.MAXLENGTH * 2 - cur_len) / 2));
		}
	}
	function getStrLen(str, event) {
		cur_len = 0;
		i = 0
		for (;
		(i < str.length) && (cur_len <= event.data.MAXLENGTH * 2); i++) {
			if (str.charCodeAt(i) > 0 && str.charCodeAt(i) < 128) {
				cur_len++;
			} else {
				cur_len += 2;
			}
		};
		return cur_len;
	};
})