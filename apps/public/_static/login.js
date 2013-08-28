// 提交表單
$(document).ready(function() { 
    $('#ajax_login_form').submit(function(){
        $(this).ajaxSubmit({
            beforeSubmit:  checkLoginForm, 
            success:       loginCallback,
            dataType: 'json'
        }); 
        return false; 
    });
    // 提交釋出前驗證
    var checkLoginForm = function() {
        if($('#account_input').val().length == 0) {
            $('#account_input').focus();
            return false;
        }
        if($('#pwd_input').val().length == 0) {
            $('#pwd_input').focus();
            return false;
        }
        return true;
    };
    // 成功後的回撥函數
    var loginCallback = function(i) {
        // var i = eval("("+e+")");
        if(i.status==1){
            $('#js_login_input').html('<p>'+i.info+'</p>').show();    
            if(i.data==0){
                window.location.href = U('public/Index/index');  
            }else{
                window.location.href = i.data;            
            }
        }else{
            $('#js_login_input').html('<p>'+i.info+'</p>').show();
        }
    };
}); 

// 登入驗證
var loginCheck = {};
// 繫結驗證事件
loginCheck.bindKeyEvent = function(objInput, blurObjInput, objLabel) {
    objInput.bind('keydown', function(event) {
        if(event.which == 13) {
            var aLen = blurObjInput.val().length;
            var pLen = objInput.val().length;
            if(aLen == 0) {
                objInput.blur()
                blurObjInput.focus();    
            } else if(aLen != 0 && pLen == 0) {
                objInput.focus();
            } else if(aLen != 0 && pLen != 0) {
                $('#ajax_login_form').submit();
            }
        }
    }); 
}
// 輸入框驗證
loginCheck.inputChecked = function(objDiv, objInput, objLabel, blurObjInput) {
    objInput.bind({
        focus: function() {
            var len = $(this).val().length;
            len == 0 && objDiv.attr('class', 'input1');
            typeof(blurObjInput) != "undefined" && loginCheck.bindKeyEvent(objInput, blurObjInput, objLabel);
        },
        keydown: function() {
            objLabel.css('display', 'none');
        },
        keyup: function() {
            var len = $(this).val().length;
            len == 0 && objLabel.css('display', '');
        },
        blur: function() {
            var len = $(this).val().length;
            if(len == 0) {
                objDiv.attr('class', 'input');
                objLabel.css('display', '');
            }
        }
    });
};
// 事件監聽
M.addModelFns({
    login_input: {
        load: function() {
            // 賬號輸入框
            var jAccount = $(this).find('li').eq(0);
            var jADiv = jAccount.find('div').eq(0);
            jADiv.attr('class', 'input');
            var jAInput = jAccount.find('input').eq(0);
            jAInput.val('');
            var jALabel = jAccount.find('label').eq(0);
            jALabel.css('display', '');
            loginCheck.inputChecked(jADiv, jAInput, jALabel);
            // 密碼輸入框
            var jPwd = jAccount.next();
            var jPDiv = jPwd.find('div').eq(0);
            jPDiv.attr('class', 'input');
            var jPInput = jPwd.find('input').eq(0);
            // 動態改變密碼域，IE6
            if(!$.browser.msie) {
                jPInput[0].type = 'password';
            }
            jPInput.val('');
            var jPLabel = jPwd.find('label').eq(0);
            jPLabel.css('display', '');
            loginCheck.inputChecked(jPDiv, jPInput, jPLabel, jAInput);
            // 聯想框
            $('#account_input').changeTips({
                divTip: ".on-changes",
                focusInput: jAInput,
                nextFocus: jPInput
            });
        }
    }
});
M.addEventFns({
    login_remember: {
        load: function() {
            var objSpan = $(this).find('span').eq(0);
            var objInput = $(this).find('input').eq(0);
            objInput.val(1);
            objSpan.attr('class', 'check-ok');
        },
        click: function() {
            var objSpan = $(this).find('span').eq(0);
            var objInput = $(this).find('input').eq(0);
            var checkedCls = objSpan.attr('class');
            if(checkedCls == "check") {
                objInput.val(1);
                objSpan.attr('class', 'check-ok');
            } else {
                objInput.val(0);
                objSpan.attr('class', 'check');
            }
        }
    }
});

/**
 * 登入流程，JQuery插件，用於顯示感知框
 */
(function($) {
    $.fn.extend({
        changeTips: function(value) {
            // 初始化選擇的類名
            value = $.extend({
                divTip: ""
            }, value);

            var $this = $(this);
            var indexLi = 0;
            // 繫結li點選事件
            $(document).click(function(event) {
                if($(event.target).is("li") && typeof($(event.target).attr('email')) != "undefined") {
                    var liVal = $(event.target).text();
                    $this.val(liVal);
                    blus();
                } else {
                    blus();
                }
            });
            // 下拉框消失
            var blus = function() {
                $(value.divTip).hide();
            }
            // 選中上下移動
            var keyChange = function(up) {
                if(up == "up") {
                    if(indexLi == 0) {
                        indexLi = $(value.divTip).find('li[rel="show"]').length - 1;
                    } else {
                        indexLi--;
                    }
                } else {
                    if(indexLi == $(value.divTip).find('li[rel="show"]').length - 1) {
                        indexLi = 0;
                    } else {
                        indexLi++;
                    }
                }
                $(value.divTip).find('li[rel="show"]').eq(indexLi).addClass("current").siblings().removeClass(); 
            }
            // 改變輸入框中的值
            var valChange = function() {
                var tex = $this.val();
                var fronts = "";
                var af = /@/;
                var regMail = new RegExp(tex.substring(tex.indexOf("@")));
                if($this.val() == "") {
                    blus();
                } else {
                    $(value.divTip).show().find('li').each(function(index) {
                        var valAttr = $(this).attr("email");
                        if(index == 0) {
                            $(this).text(tex).addClass('current').siblings().removeClass();
                        }
                        if(index > 0) {
                            if(af.test(tex)) {
                                fronts = tex.substring(tex.indexOf("@"), 0);
                                $(this).text(fronts + valAttr);
                                if(regMail.test($(this).attr("email"))) {
                                    $(this).attr('rel', 'show');
                                    $(this).css({position:'static', visibility:'inherit'});
                                } else {
                                    if(index > 0) {
                                        $(this).attr('rel', 'hide');
                                        $(this).css({position:'absolute', visibility:'hidden'});
                                    }
                                }
                            } else {
                                $(this).text(tex + valAttr);
                            }
                        }
                    });
                }
            }
            // 瀏覽器的輸入的相容性
            if($.browser.msie && $.browser.version != '9.0') {
                $(this).bind("propertychange", function() {
                    valChange();
                });
            } else {
                $(this).bind("input", function() {
                    valChange();
                });
            }
            // 觸碰後的樣式
            $(value.divTip).find('li').hover(function() {
                $(this).addClass("current").siblings().removeClass();
            });
            // 繫結按鍵事件
            $this.keydown(function(event) {
                if(event.which == 38) {
                    // 按上
                    keyChange("up");
                } else if(event.which == 40) {
                    // 按下
                    keyChange();
                } else if(event.which == 13) {
                    // 按回車
                    var liVal = $(value.divTip).find('li[rel="show"]').eq(indexLi).text();
                    $this.val(liVal);
                    blus();
                    // 焦點定位
                    typeof(value.nextFocus) != "undefined" && (value.focusInput.val().length != 0) && value.nextFocus.focus();
                } else if(event.which == 9) {
                    blus();
                }
            });
        }
    });
})(jQuery);

// 頁面在載入設定可視視窗寬度與高度
$(function () {
    changeSize();
});

// 改變瀏覽器可視視窗寬度與高度
$(window).resize(function () {
    changeSize();
});

var changeSize = function () {
    var cssObj = {};
    cssObj.width = $(this).width();
    // cssObj.height = $(this).height();
    $('#login_bg').css(cssObj);
};