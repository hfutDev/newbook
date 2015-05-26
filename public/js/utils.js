(function ($) {
    String.prototype.trim = function () {
        return this.replace(/(^\s*)|(\s*$)/g, "");
    };
    /**
     * 自动缩放图片
     * @function
     * @param {Boolean} scaling 是否自动加载
     * @param {Number} width 图片容器的宽度
     * @param {String} loadpic loading时或图片未能加载时的替代图片
     * @param {Number} height 图片容器的高度
     */
    $.fn.resizeImage = function (scaling, width, height, loadpic) {
        // alert('dao')
        if (loadpic == null)
            loadpic = "/images/loading.gif";
        return this.each(function () {
            var t = $(this);
            var src = $(this).attr("src");
            var img = new Image();
            img.src = src;
            // if(src!=loadpic){
            // 	return;
            // }
            //自动缩放图片
            var autoScaling = function () {
                if (scaling) {
                    if (img.width > 0 && img.height > 0) {
                        if (img.width / img.height >= width / height) {
                            if (img.width > width) {
                                t.width(width);
                                t.height((img.height * width) / img.width);
                            } else {
                                t.width(img.width);
                                t.height(img.height);
                            }
                        } else {
                            if (img.height > height) {
                                t.height(height);
                                t.width((img.width * height) / img.height);
                            } else {
                                t.width(img.width);
                                t.height(img.height);
                            }
                        }
                    }
                }
            };
            //处理ff下会自动读取缓存图片
            if (img.complete) {
                autoScaling();
                return;
            }
            $(this).attr("src", "");
            var loading = $("<img class=\"loading\" alt=\"加载中...\" title=\"图片加载中...\" src=\"" + loadpic + "\" />");
            t.hide();
            t.after(loading);
            $(img).load(function () {
                autoScaling();
                loading.remove();
                t.attr("src", this.src);
                t.show();
                return false;
            });
        });
    };
    $.fn.resizeImage2 = function (scaling, width, height, loadpic) {
        // alert('dao')
        if (loadpic == null)
            loadpic = "/images/loading.gif";
        return this.each(function () {
            var t = $(this);
            var src = $(this).attr("src");
            var img = new Image();
            img.src = src;
            // if(src!=loadpic){
            // 	return;
            // }
            //自动缩放图片
            var autoScaling = function () {
                if (scaling) {
                    if (img.width > 0 && img.height > 0) {
                        if (img.width / img.height >= width / height) {
                            if (img.width > width) {
                                t.width(width);
                                t.height((img.height * width) / img.width);
                            } else {
                                t.width(img.width);
                                t.height(img.height);
                            }
                        } else {
                            if (img.height > height) {
                                t.height(height);
                                t.width((img.width * height) / img.height);
                            } else {
                                t.width(img.width);
                                t.height(img.height);
                            }
                        }
                    }
                }
            };
            //处理ff下会自动读取缓存图片
            if (img.complete) {
                autoScaling();
                return;
            }
            // $(this).attr("src", "");
            // var loading = $("<img class=\"loading\" alt=\"加载中...\" title=\"图片加载中...\" src=\"" + loadpic + "\" />");
            t.hide();
            // t.after(loading);
            // $(img).load(function() {
            autoScaling();
            // loading.remove();
            t.attr("src", this.src);
            t.show();
            return false;
            // });
        });
    };
    $.fn.dialog = function (id) {
        // 登陆框的显影 -- start
        var _this = $(this);
        var bg_cover = '<div class="bg-cover"></div>';
        var tpll = '<div class="base-dialog"><div class="base-dialog-handle"><span class="base-dialog-title"></span><span class="base-dialog-close" title="关闭">×</span></div><div class="base-dialog-main"></div></div>"';
        var cssText = '"<style type="text/css">.bg-cover{position: fixed;top: 0;left: 0;height: 100%;width: 100%;background-color: #000;opacity: 0.60;filter:alpha(opacity=60);z-index:9990;-webkit-animation-name: opacity;-webkit-animation-duration: 0.3s;-webkit-animation-timing-function: ease-in-out;;}.base-dialog{border-radius: 10px 10px 0 0;background-color: #fdf7e7;position: fixed;z-index: 9999;opacity: 1;filter:alpha(opacity=100);left: 50%;top: 50%;margin-left: -150px;margin-top: -150px;width: 390px;}.base-dialog-handle{border-radius: 10px 10px 0 0;background-color: #ec7150;font-family: \'微软雅黑\';height: 40px;width: 100%;overflow: hidden;color: #000;line-height: 40px;margin-bottom: 1px;}.base-dialog-title{float: left;font-size: 16px;font-weight: bold;color: #fff;margin-left: 20px;}.base-dialog-close{font-size: 30px;float: right;margin-right: 10px;color: #dacbcb;cursor: pointer;}.base-dialog-close:hover{color: #fbfbfb;}.base-dialog-main{background-image: -webkit-linear-gradient(top,#fcedcb,#fffffe);background-image: -moz-linear-gradient(top,#fcedcb,#fffffe);padding: 0 20px 10px;}.switchover-btn{font-style: italic;line-height: 30px;color: #ec7150;}#log-username,#log-password{width:153px;}.base-dialog-main p>label{font-size: 15px;}.reg-submit-btn{padding: 4px 46px;font-size: 14px;}.base-dialog-main p i{left: 270px;}.log-submit-btn{font-size: 14px;padding: 4px 12px;}.forget-pw{color: #c16bf2;display: inline-block;margin-left: 13px;}.validation_code_a{position: absolute;top: 1px;left: 195px;height: 30px;width: 70px;overflow: hidden}</style>"';
        var css = $(cssText);
        var tpl = $(tpll);
        var bg = $(bg_cover);
        var close_btn = tpl.find('.base-dialog-close');
        close_btn.on('click', dialog_hide);
        keyDown(27, dialog_hide); // esc关闭
        _this.click(function () { // 登陆框的显影
            if ($('.reg').data('dialog') != 1 && $('.log').data('dialog') != 2) { // 第一次触发时
                $("body").append($(bg_cover));
                $("body").append(tpl);
                $("head").append($(css));
                // $('.bg-cover').on('click',dialog_hide); // 点击遮盖层关闭
            } else {
                $('.bg-cover')[0].style.display = 'block';
                $('.base-dialog')[0].style.display = 'block';
            }
            if (id == 2 && $('.log').data('dialog') != 2) { // 登录框第一次触发时
                log_creat();
            } else if (id == 2 && $('.log').data('dialog') == 2) {
                $('.log')[0].style.display = 'block';
            }
            return false;
        });
        function dialog_hide() { // 切换dialog
            $('.bg-cover')[0].style.display = 'none';
            $('.base-dialog')[0].style.display = 'none';
            // if ($('.reg').data('dialog')) {
            // 	$('.reg')[0].style.display='none';
            // };
            if ($('.log').data('dialog')) {
                $('.log')[0].style.display = 'none';
            }
            ;
            $('.base-dialog').find('i').html('').end().find('input[type!="submit"]').val('');
        }

        function log_creat() { // 创建登录框
            $((".base-dialog-title")).empty().html("登录");
            var main_tpl = '"<div class="log"><p style="text-align:right;"><a href="javascript:void(0)" class="switchover-btn toreg">木有账号/快速注册</a></p><form action="" method="post" name="log-form" id="log-form" style="margin-left:-20px;text-align: left;"><p><label for="log-username">登陆账号</label><input type="text" tabindex="1" name="log-username" id="log-username"><i></i></p><p><label for="log-password">登陆密码</label><input type="password" tabindex="2" name="log-password" id="log-password"><i></i></p><p><label for="validation_code">验证码</label><input type="text" tabindex="3" name="validation_code" id="validation_code" style="width:76px;"><a href="javascript:void(0)" class="validation_code_a"><img id="code" title="点击切换" onclick="creatCode()" src="/global/vcode"></a><i></i></p><p style="text-align: center;"><input type="submit" value="提 交" name="log-btn" tabindex="4" class="submit-btn log-submit-btn"><a href="javascript:void(0)" class="forget-pw">忘记密码？</a></p></form></div>"';
            $(".base-dialog-main").append($(main_tpl));
            $('.log').data('dialog', '2');
        }
    };
    /**
     * 错误动画
     * @function
     * @param {Number} val 输入框原先位置的marginLeft
     */
    $.fn.error_aimat = function (val) { // input错误动画
        var currVal = (val == null ? 0 : parseInt(val, 10));
        $(this).stop()
            .animate({
                'margin-left': currVal - 10
            }, 100)
            .animate({
                'margin-left': currVal + 20
            }, 100)
            .animate({
                'margin-left': currVal - 10
            }, 100)
            .animate({
                'margin-left': currVal + 20
            }, 100)
            .animate({
                'margin-left': currVal
            }, 100)
    }
})(jQuery);
$(function () {
    // alert('dao ')
    var pass_reg = new RegExp(/^[A-Za-z0-9_\@\!\#\$\%\^\&\*\.\~\?]{6,20}$/);
    var name_reg = new RegExp(/^[a-zA-Z0-9_\u4E00-\u9FFF]{6,20}$/);
    $('#validation_code').live('focus', {target: "p"}, valCodeCheck);

    $('.log-submit-btn').live("click", function () { //登陆输入框动画 -- start
        var _this = $(this);
        var username_val = $('#log-username').val();
        var password_val = $('#log-password').val();
        var code_val = $('#validation_code').val();
        var code_length = parseInt($('#validation_code').val().length, 10);
        if (username_val == '') {
            //用户名为空
            $("#log-username").error_aimat(10);
        } else if (password_val == '') {
            $("#log-password").error_aimat(10);
        } else if ($("#validation_code").data('yeah') != 1) {
            $("#validation_code").error_aimat(10);
        } else {
//			alert('dao')
            var to_href = $('.to-href').val();
            $.ajax({
                type: 'POST',
                url: '/global/login',
                // dataType: 'json',
                data: {
                    'name': username_val,
                    'pass': password_val,
                    'valCode': code_val,
                    'toHref': to_href  // 登录页面跳转的值
                },
                success: function (data) {
                    if (typeof(data) == "string") {
                        //alert(data);
                        if (data == 2) {
//							alert(data)
                            //用户名或密码错误动画
                            $("#log-username").siblings("i").html("账号或密码错误")
                                .end().error_aimat(10);
                        } else {
                            if (!_this.closest('#log-form').hasClass('whole-login')) {
                                replace_reg_log(data);
                            } else {
                                var data = eval("(" + data + ")");
                                window.location.href = data.toHref; // 登录页面跳转的值
                            }
                        }
                    }
                } // end of success()
            })
        }
        ;
        return false;
    }); //登陆输入框动画 -- end
    // 检查注册成功与否=-------start----
    // var Vname = false,
    // 	pwd = false,
    // 	Code = false;
    // 	// 检查注册成功与否 若任一变量为false则不能注册=-------start----
    // 	$(".reg-submit-btn").live('click',function(){
    // 		if (Vname && pwd && Code) {
    // 			// alert('1')
    // 			$.ajax({
    // 				type: "POST",
    // 				url: "userAction.php?a=regist",
    // 				data: {
    // 					name: encodeURIComponent($("#reg-username").val()),
    // 					pass: $("#reg-password").val()
    // 				},
    // 				success: function() {
    // 					// alert(data);
    // 					if (typeof data =="string") {
    // 						alert("恭喜你，注册成功！");
    // 						replace_reg_log(data);
    // 					} else {
    // 						alert("对不起，注册失败，请重新注册");
    // 					}
    // 				}
    // 			}); // end----ajax
    // 		} else {
    // 		$("#register-form input").blur();
    // 		} // end -- if eles
    // 		return false;
    // 	})// 检查注册成功与否 若任一变量为false则不能注册=------- end----

    $(".sort-li-wrap").hover(function () { // 导航栏分类显影
        var _this = $(this);
        var _wrap = $(".sort-wrap");
        _this.find(".arrow-em").css({"color": "#ec7150"}).stop().animate({top: "0px"}, 300)
            .end().find(".arrow-span").css({"color": "#fff"}).stop().animate({top: "3px"}, 200);
        var Top = parseInt($(".nav").height());
        var Left = 0;
        var styleString = "display:block;top:" + Top + "px;left:" + Left + "px;z-index:999"
        _wrap.attr("style", styleString);
        $(".sortBy-lable").find("ul")[0].style.display = "none";
        $(".sortBy-category").find("ul")[0].style.display = "none";
        $(".sortBy-category").mouseover(function () {
            $(".BBoard")[0].style.display = "block";
            $(".BBoard")[1].style.display = "none";
            $(".sortBy-college").find("ul").css({display: "none"});
            $(".sortBy-lable").find("ul").css({display: "none"});
            $(this).find("ul").css({
                display: "block",
                top: "0px",
                left: "110px"
            }).find("li:odd").css({background: "#ede7db"});
            return false;
        });
        $(".sortBy-lable").mouseover(function () {
            _this.find("#sort-btn").addClass("nav-a-current")
                .find(".arrow-em").css({"color": "#ec7150", top: "0px"})
                .end().find(".arrow-span").css({"color": "#fff", top: "3px"});

            $(".BBoard")[0].style.display = "none";
            $(".BBoard")[1].style.display = "block";
            $(".sortBy-college").find("ul").css({display: "none"});
            $(".sortBy-category").find("ul").css({display: "none"});
            $(this).find("ul").css({
                display: "block",
                top: "37px",
                left: "110px"
            }).find("li:odd").css({background: "#ede7db"});
            return false;
        });
        //});
    }, function () {
        var _this = $(this);
        _this.find("#sort-btn").removeClass("nav-a-current")
            .find(".arrow-em").css({"color": "#fff", top: "-3px"})
            .end().find(".arrow-span").css({"color": "#ec7150", top: "-6px"});
        $(".sort-wrap")[0].style.display = "none";
        return false;
    });// 导航栏分类显影 --end
    // $('#header-register').dialog(1); // 生成注册框
    //$('#header_login').dialog(2);	// 生成登录框

    $('.search-input').live('blur', function () {
        if ('' != $(this).val()) {
            $(this)[0].style.backgroundImage = 'none';
        }
    })
});
// 验证码检测
function valCodeCheck(event) {
    var $this = $(this);
    var tar = '$(this).closest("' + event.data.target + '").find("i")';
    var _i = eval(tar);
    $this.bind('keyup', function () {
        var val = $(this).val().trim();
        if (val.length != 4) {
            _i.html("");
            $this.data('yeah', '0');
        } else if (val.length == 4) {
            $.ajax({
                type: "POST",
                url: "/global/vcodechk",
                data: {
                    valCode: val
                },
                success: function (data) {
                    if ("sucess" == data) {
                        _i.html('<img src="/images/right-icon.png" align="absmiddle" />');
                        $this.data('yeah', '1');
                    } else {
                        _i.html("验证码错误");
                        $this.data('yeah', '0');
                    }
                }
            })
        }
    })
}
/**
 * 键盘事件检测
 * @function
 * @param {number} keyCode
 * @param {Function} fun 函数名
 */
function keyDown(keyCode, fun) {
    $(window).keydown(function (e) {
        var e = e || event;
        var currKey = e.keyCode || e.which || e.charCode;
        if (currKey == keyCode) {
            fun();
        }
    })
}
function dialog_to_hide() {
    $('.bg-cover').remove();
    $('.base-dialog').remove();
}
// 注册or登录成功之后销毁dialog，替换
function replace_reg_log(data) {
    //if(typeof data =="string")
    var data = eval("(" + data + ")");
    dialog_to_hide();  // 关闭弹出框
    $('#header_login').replaceWith('<a href="' + data.per_href + '" title="点击进入个人中心">' + data.username + '</a>');
    $('#header-register').replaceWith('<a href="' + data.logout_href + '" id="logout">注销</a>');
}
function appendjs() {
    var permsg = '<script type=\"text/javascript\" src=\"/js/permsg.js\"></script>';
    var emotion = '<script type=\"text/javascript\" src=\"/js/emotion.js\"></script>';
    $('head').append($(permsg));
    $('head').append($(emotion));
}

function checkWord(event) {
    // alert('w i'.charCodeAt(1))
    var str = $.trim($(this).val());
    cur_len = getStrLen(str, event);
    if (cur_len > (event.data.MAXLENGTH) * 2) {
        // alert(str)
        $(this).val(str.substring(0, cur_len - 1));
    } else {
        $(this).siblings('.wordCheck').find('.wordChange').html(Math.floor((event.data.MAXLENGTH * 2 - cur_len) / 2));
    }
}
function getStrLen(str, event) {
    cur_len = 0;
    i = 0;
    for (; (i < str.length) && (cur_len <= event.data.MAXLENGTH * 2); i++) {
        if ((str.charCodeAt(i) > 0 && str.charCodeAt(i) < 128) || 32 == str.charCodeAt(i)) {
            cur_len++;
        } else {
            cur_len += 2;
        }
    }
    return cur_len;
}
