/**
 * ThinkSNS核心Js物件
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
var _core = function() {
	// 核心通用的載入源函數
	var obj = this;
	// 載入檔案方法
	this._coreLoadFile = function() {
		var temp = new Array();
		var tempMethod = function(url, callback) {
			// 第二次呼叫的時候就不=0了
			var flag = 0;
			for(i in temp) {
				if(temp[i] == url) {
					flag = 1;
				}
			}
			if(flag == 0) {
				// 未載入過
				temp[temp.length] = url;	
				// JQuery的ajax載入檔案方式，如果有樣式檔案，同理在此引入相關樣式檔案
				$.getScript(url, function() {	
					if("undefined" != typeof(callback)) {
						if("function" == typeof(callback)) {
							callback();
						} else {
							eval(callback);
						}
					}
				});
			} else {
				if("undefined" != typeof(callback)) {
					// 利用setTimeout 避免未定義錯誤
					setTimeout(callback, 200);	
				}
			}
		};
		// 返回內部包函數，供外部呼叫並可以更改temp的值
		return tempMethod;
	};
	// 載入CSS檔案
	this._loadCss = function() {
		var temp = new Array();
		var tempMethod = function(url) {
			// 第二次呼叫的時候就不=0了
			var flag = 0;
			for(i in temp) {
				if(temp[i] == url) {
					flag = 1;
				}
			}
			if(flag == 0) {
				// 未載入過
				temp[temp.length] = url;	
				var css = '<link href="'+THEME_URL+'/js/tbox/box.css" rel="stylesheet" type="text/css">';
				$('head').append(css);
			}
		};
		// 返回內部包函數,供外部呼叫並可以更改temp的值
		return tempMethod;
	};
	/**
	 * 時間插件源函數
	 * 利用必包原理只載入一次js檔案,其他類似功能都可以參照此方法
	 * 需要提前引入jquery.js檔案
	 * @author yangjs
	 */
	this._rcalendar = function(text, mode, refunc) {
		// 標記值 
		var temp = 0;	
		var tempMethod = function(t, m, r) {
			// 第二次呼叫的時候就不=0了
			if(temp == 0) {	
				// JQuery的ajax載入檔案方式，如果有樣式檔案，同理在此引入相關樣式檔案
				$.getScript(THEME_URL+'/js/rcalendar.js', function() {	
					rcalendar(t, m, r);
				});
			} else {
				rcalendar(t, m, r);
			}
			temp++;
		};
		// 返回內部包函數，供外部呼叫並可以更改temp的值
		return tempMethod;	
	};
	/**
	 * 生成IMG的html塊
	 */
	this._createImageHtml = function() {
		var _imgHtml = '';
		var _c = function() {
			if(_imgHtml == '') {
				$.post(U('public/Feed/getSmile'), {}, function(data) {
					for(var k in data) {
						_imgHtml += '<a href="javascript:void(0)" title="'+data[k].title+'" onclick="core.face.face_chose(this)";><img src="'+ THEME_URL +'/image/expression/'+data[k].type+'/'+ data[k].filename +'" width="24" height="24" /></a>';	
					}
					_imgHtml += '<div class="c"></div>';
					$('#emot_content').html(_imgHtml);
				}, 'json');
			} else {
				$('#emot_content').html(_imgHtml);
			}
		};
		return _c;
	};
}

// 核心物件
var core = new _core();

/**
 * 核心的插件列表
 */

//微博載入檔案，支援回撥函數 呼叫方式core.loadFile(url,callback)
core.loadFile = core._coreLoadFile();
core.loadCss = core._loadCss();

/**
 * 核心插件自動生成的工廠函數
 * 這裡用到了js的工廠模式等設計模式
 * 
 * 使用方法：將ｊｓ插件寫到plugins/下的對應檔案下，檔名必須與插件物件同名，如core.at.js
 * JS 插件裡面需要有一個_init 函數，根據傳入參數真正呼叫 init函數 
 * 
 * 如：core.plugInit('searchUser',$(this))；
 * 其中searchUser表示插件的名稱是core.searchUser.js
 * $(this) 為 init的第一個參數
 * 
 * @author yangjs
 */
core.plugInit = function() {
	if(arguments.length > 0) {
		var arg = arguments;
		var back = function() {
			eval("var func = core." + arg[0] + ";");
			if("undefined" != typeof(func)) {
				func._init(arg);	
			}
		};
		var file = THEME_URL + '/js/plugins/core.' + arguments[0] + '.js';
		core.loadFile(file, back);
	}
};
//與上面方法類似 只不過可以自己寫回撥函數（不主動執行init）
core.plugFunc = function(plugName,callback){
	var file = THEME_URL+'/js/plugins/core.'+plugName+'.js';
	core.loadFile(file,callback);
};

/**
 * 優化setTimeout函數
 * @param func
 * @param time
 */
core.setTimeout = function(func,time){
//	var type = typeof(func);
//	if("undefined" == type){
		setTimeout(func, time);
//	}else{
//		if("string" == type){
//			eval(func);
//		}else{
//			func();
//		}
//	}	

};
// 獲取物件編輯框內的可輸入數字
core.getLeftNums = function(obj) {
	var str = obj.innerHTML;
	// 替換br標籤
	var imgNums = $(obj).find('img').size();
	// 判斷是否為空
	var _str = str.replace(new RegExp("<br>","gm"),"");	
	_str = _str.replace(/[ ]|(&nbsp;)*/g, "");
	// 判斷字數是否超過，一個空格算一個字
	_str = str.replace(/<[^>]*>/g, "");		// 去掉所有HTML標籤
	_str = trim(_str,' ');
	
	if(imgNums <1 ) {
		var emptyStr = _str.replace(/&nbsp;/g,"").replace(/\s+/g,"");
		if(emptyStr.length == 0) {
			return initNums;
		}
	}
	_str = _str.replace(/&nbsp;/g,"a"); 	// 由於可編輯DIV的空格都是nbsp 所以這麼算

	return initNums - getLength(_str) - imgNums;
};
core.getLength = function(str, shortUrl) {
	str = str + '';
	if (true == shortUrl) {
		// 一個URL當作十個字長度計算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
							.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};
// 一些自定義的方法
// 生成表情圖片
core.createImageHtml = core._createImageHtml();
//日期控制項,呼叫方式 core.rcalendar(this,'full')
//this 也可以替換為具體ID,full表示時間顯示模式,也可以參考rcalendar.js內的其他模式
core.rcalendar = core._rcalendar();	


//臨時存儲機制 適用於分割開存儲的內容

core.stringDb = function(obj,inputname,tags){
    this.inputname = inputname;
    this.obj  = obj;
    if(tags != ''){
    	this.tags = tags.split(',');	
    }else{
    	this.tags = new Array();
    }
    this.taglist = $(obj).find('ul');
    this.inputhide = $(obj).find("input[name='"+inputname+"']");
};

core.stringDb.prototype = {
	init:function(){
		if(this.tags.length > 0){
			var html = '';
			for(var i in this.tags){
				if(this.tags[i] != ''){
					html +='<li><label>'+this.tags[i]+'</label><em>X</em></li>';
				}
			}
			this.taglist.html(html);
			this.bindUl();
		}
	},
	bindUl:function(){
		var _this = this;
		this.taglist.find('li').click(function(){
			_this.remove($(this).find('label').html());
		});
	},
    add:function(tag){
    	var _tag = tag.split(',');
    	var _this = this;
    	var add = function(t){
    		for(var i in _this.tags){
    			if(_this.tags[i] == t){
    				return false;
    			}
    		}
    		var html = '<li><label>'+t+'</label><em>X</em></li>';
    		_this.tags[_this.tags.length] = t;
    		_this.taglist.append(html);		
    	};	

    	for(var ii in _tag){ 
    		if(_tag[ii] != ''){
    			add(_tag[ii]);
    		}
    	}
    	
    	this.inputhide.val(this.tags.join(','));
    	this.bindUl();
    },
    remove:function(tag){
    	var del = function(arr,n){
    		if(n<0){
    			return arr;
    		}else{
    			return arr.slice(0,n).concat(arr.slice(n+1,arr.length))
    		}
    	}

    	for(var i in this.tags){
    		if(this.tags[i] == tag){
    			this.tags = del(this.tags,parseInt(i));
    			this.taglist.find('li').eq(i).remove();
    			this.inputhide.val(this.tags.join(','));
    		}
    	}
    }	
};

/*** 核心Js函數庫 ***/
/**
 * 模擬TS的U函數，需要預先定義JS全局變數SITE_URL和APPNAME
 * @param string url 連結地址
 * @param array params 連結參數
 * @return string 組裝後的連結地址
 */
var U = function(url, params) {
	var website = SITE_URL+'/index.php';
	url = url.split('/');
	if(url[0]=='' || url[0]=='@')
		url[0] = APPNAME;
	if (!url[1])
		url[1] = 'Index';
	if (!url[2])
		url[2] = 'index';
	website = website+'?app='+url[0]+'&mod='+url[1]+'&act='+url[2];
	if(params) {
		params = params.join('&');
		website = website + '&' + params;
	}
	return website;
};
/**
 * 窗體物件，全站使用，統一窗體介面
 */
var ui = {
	/**
	 * 浮屏顯示訊息，提示資訊框
	 * @param string message 資訊內容
	 * @param integer error 是否是錯誤樣式，1表示錯誤樣式、0表示成功樣式
	 * @param integer lazytime 提示時間
	 * @return void
	 */
	showMessage: function(message, error, lazytime) {
		var style = (error=="1") ? "html_clew_box clew_error" : "html_clew_box";
		var ico = (error == "1") ? 'ico-error' : 'ico-ok';
		var html = '<div class="'+style+'" id="ui_messageBox" style="display:none">\
					<div class="html_clew_box_con" id="ui_messageContent">\
					<i class="'+ico+'"></i>'+message+'</div></div>';		
		var _u = function() {
			for (var i = 0; i < arguments.length; i++) {
		        if (typeof arguments[i] != 'undefined') return false;
			}
		    return true;
		};
		// 顯示提示彈窗
		ui.showblackout();
		// 將彈窗載入到body後
		$(html).appendTo(document.body);
		// 獲取高寬
		var _h = $('#ui_messageBox').height();
		var _w = $('#ui_messageBox').width();
		// 獲取定位值
		var left = ($('body').width() - _w)/2 ;
		var top  = $(window).scrollTop() + ($(window).height()-_h)/2;
		// 添加彈窗樣式與動畫效果（出現）
		$('#ui_messageBox').css({
			left:left + "px",
			top:top + "px"
		}).fadeIn("slow",function(){
			$('#ui_messageBox').prepend('<iframe style="z-index:;position: absolute;visibility:inherit;width:'+_w+'px;height:'+_h+'px;top:0;left:0;filter=\'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)\'"'+
		 	'src="about:blank"  border="0" frameborder="0"></iframe>');
		});
		// 添加彈窗動畫效果（消失）
		setTimeout(function() { 
			$('#ui_messageBox').find('iframe').remove();
			$('#ui_messageBox').fadeOut("fast", function() {
			  ui.removeblackout();
			  $('#ui_messageBox').remove();
			});
		} , lazytime*1000);
	},
	/**
	 * 添加彈窗
	 * @return void
	 */
	showblackout: function() {
		if($('.boxy-modal-blackout').length > 0) {
			// TODO:???
    	} else {
    		var height = $('body').height() > $(window).height() ? $('body').height() : $(window).height();
    		$('<div class ="boxy-modal-blackout" ><iframe style="z-index:-1;position: absolute;visibility:inherit;width:'+$('body').width()+'px;height:'+height+'px;top:0;left:0;filter=\'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)\'"'+
		 'src="about:blank"  border="0" frameborder="0"></iframe></div>')
        	.css({
        	    height:height+'px',width:$('body').width()+'px',zIndex: 999, opacity: 0.5
        	}).appendTo(document.body);
    	}
	},
	/**
	 * 移除彈窗
	 * @return void
	 */
	removeblackout: function() {
		if($('#tsbox').length > 0) {
		 	if(document.getElementById('tsbox').style.display == 'none'){
		 		$('.boxy-modal-blackout').remove();
		 	}	
		 } else {
		 	$('.boxy-modal-blackout').remove(); 	
		 }
	},
	/**
	 *  操作成功顯示API
	 * @param string message 資訊內容
	 * @param integer time 展示時間
	 * @return void
	 */
	success: function(message, time) {
		var t = "undefined" == typeof(time) ? 1 : time;
		ui.showMessage(message, 0, t);
	},
	/**
	 * 操作出錯顯示API
	 * @param string message 資訊內容
	 * @param integer time 展示時間
	 * @return void
	 */
	error: function(message, time) {
		var t = "undefined" == typeof(time) ? 2 : time;
		ui.showMessage(message, 1, t);
	},
	/**
	 * 確認彈框顯示API - 浮窗型
	 * @example
	 * 可以加入callback，回撥函數
	 * @param object o 定位物件
	 * @param string text 提示語言
	 * @param string|function _callback 回撥函數名稱
	 * @return void
	 */
	confirm: function(o, text, _callback) {
		// 判斷彈窗是否存在
		document.getElementById('ts_ui_confirm') !== null && $('#ts_ui_confirm').remove();
		var callback = "undefined" == typeof(_callback) ? $(o).attr('callback') : _callback;
		text = text || L('PUBLIC_ACCONT_TIPES');
		text = "<i class='ico-error'></i>"+text;
		this.html = '<div id="ts_ui_confirm" class="ts_confirm"><div class="layer-mini-info"><dl><dt class="txt"> </dt><dd class="action"><a class="btn-green-small mr10 btn_ok" href="javascript:void(0)"><span>'+L('PUBLIC_QUEDING')+'</span></a><a class="btn-cancel" href="javascript:void(0)"><span>'+L('PUBLIC_QUXIAO')+'</span></a></dd></dl></div></div>';
		$('body').append(this.html);
		var position = $(o).offset();
		$('#ts_ui_confirm').css({"top":position.top+"px","left":position.left+"px","display":"none"});
		$("#ts_ui_confirm .txt").html(text);
		$('#ts_ui_confirm').fadeIn("fast");
		$("#ts_ui_confirm .btn-cancel").one('click',function(){
			$('#ts_ui_confirm').fadeOut("fast");
			// 修改原因: ts_ui_confirm .btn_b按鈕會重複提交
			$('#ts_ui_confirm').remove();
			return false;
		});
		$("#ts_ui_confirm .btn_ok").one('click',function(){
			$('#ts_ui_confirm').fadeOut("fast");
			// 修改原因: ts_ui_confirm .btn_b按鈕會重複提交
			$('#ts_ui_confirm').remove();
			if("undefined" == typeof(callback)){
				return true;
			}else{
				if("function"==typeof(callback)){
					callback();
				}else{
					eval(callback);
				}
			}
		});
		$('body').bind('keyup', function(event) {
            $("#ts_ui_confirm .btn_ok").click();
        });
		return false;
	},
	/**
	 * 確認框顯示API - 彈窗型
	 * @param string title 彈窗標題
	 * @param string text 提示語言
	 * @param string|function _callback 回撥函數名稱
	 * @return void
	 */
	confirmBox: function(title, text, _callback) {
		this.box.init(title);
		text = text || L('PUBLIC_ACCONT_TIPES');
		text = "<i class='ico-error'></i>"+text;

		var content = '<div class="pop-create-group"><dl><dt class="txt">'+ text + '</dt><dd class="action"><a class="btn-green-small mr10" href="javascript:void(0)"><span>'+L('PUBLIC_QUEDING')+'</span></a><a class="btn-cancel" href="javascript:void(0)"><span>'+L('PUBLIC_QUXIAO')+'</span></a></dd></dl></div>';

		this.box.setcontent(content);
		this.box.center();

		var callback = "undefined" == typeof(_callback) ? $(o).attr('callback') : _callback;

		var _this = this;
		$("#tsbox .btn-cancel").one('click',function(){
			$('#tsbox').fadeOut("fast",function(){
				$('#tsbox').remove();	
			});
			_this.box.close();
			return false;
		});
		$("#tsbox .btn-green-small").one('click',function(){
			$('#tsbox').fadeOut("fast",function(){
				$('#tsbox').remove();
			});
			_this.box.close();
			if("undefined" == typeof(callback)){
				return true;
			}else{
				if("function"==typeof(callback)){
					callback();
				}else{
					eval(callback);
				}
			}
		});
		return false;
	},
	/**
	 * 私信彈窗API
	 * @param string touid 收件人ID
	 * @return void
	 */
	sendmessage: function(touid, editable) {
		if(typeof(editable) == "undefined" ) {
			editable = 1;
		}
		touid = touid || '';
		this.box.load(U('public/Message/post')+'&touid='+touid+'&editable='+editable, L('PUBLIC_SETPRIVATE_MAIL'));
	},
	/**
	 * @Me彈窗API
	 * @param string touid @人ID
	 * @return void
	 */
	sendat: function(touid) {
		touid = touid || '';
		this.box.load(U('public/Mention/at')+'&touid='+touid, '@TA');
	},
	/**
	 * 彈窗釋出微博
	 * @param string title 彈窗標題
	 * @param string initHTML 插入內容
	 * @return void
	 */
	sendbox: function(title, initHtml,channelID) {
		if($.browser.msie) {
			initHtml = encodeURI(initHtml);
		}
		initHtml = initHtml.replace(/\#/g, "%23"); 
		this.box.load(U('public/Index/sendFeedBox')+'&initHtml='+initHtml+'&channelID='+channelID, title,function(){
			$('#at-view').hide();
		});
	},
	/**
	 * 回覆彈窗API
	 * @param integer comment_id 評論ID
	 * @return void
	 */
	reply: function(comment_id) {
		this.box.load(U('public/Comment/reply')+'&comment_id='+comment_id,L('PUBLIC_RESAVE'),function (){$('#at-view').hide();});
	},
	groupreply: function(comment_id,gid) {
		this.box.load(U('group/Group/reply')+'&gid='+gid+'&comment_id='+comment_id,L('PUBLIC_RESAVE'),function (){$('#at-view').hide();});
	},
	/**
	 * 選擇部門彈窗API - 暫不使用
	 */
	changeDepartment: function(hid,showname,sid,nosid,notop) {
		this.box.load(U('widget/Department/change')+'&hid='+hid+'&showName='+showname+'&sid='+sid+'&nosid='+nosid+'&notop='+notop,L('PUBLIC_DEPATEMENT_SELECT'));
	},
	/**
	 * 自定彈窗API介面
	 */
	box: {
		WRAPPER: '<div class="wrap-layer" id="tsbox" style="display:none">\
     			  <div class="content-layer">\
     			  <div class="layer-content" id="layer-content"></div>\
     			  </div></div>',
		inited: false,
		IE6: (jQuery.browser.msie && jQuery.browser.version < 7),
		init: function(title, callback) {
			this.callback = callback;
			// 彈窗中隱藏小名片
			if("undefined" != typeof(core.facecard)){
				core.facecard.dohide();
			}

			if($('#tsbox').length >0) {
				return false;
			} else {
				$('body').prepend(this.WRAPPER);
			}
			var url = THEME_URL+'/js/tbox/box.css';
			core.loadCss(url);
			// 添加頭部
			if("undefined" != typeof(title)) {
				$("<div class='hd'>"+title+"<a class='ico-close' href='#'></a></div>").insertBefore($('#tsbox .layer-content'));
			}
			
			ui.showblackout();
			
			$('#tsbox').stop().css({width: '', height: ''});
			// 添加鍵盤事件
			jQuery(document.body).bind('keypress.tsbox', function(event) {
				var key = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
				if (key == 27) {
					jQuery(document.body).unbind('keypress.tsbox');
					ui.box.close(callback);
					return false;
				}
			});
			// 關閉彈窗，回撥函數
			$('#tsbox').find('.ico-close').click(function() {
				ui.box.close(callback);
				return false;
			});
			
			this.center();
			var show = function(){
				$('#tsbox').show();
			}
			setTimeout(show, 200);
			
			$('#tsbox').draggable({ handle: '.hd' });

			$('.hd').mousedown(function(){
				$('.mod-at-wrap').remove();
			});
		},
		/**
		 * 設定彈窗中的內容
		 * @param string content 內容資訊
		 * @return void
		 */
		setcontent: function(content) {
			$('#layer-content').html(content);
		},
		/**
		 * 關閉視窗
		 * @param function fn 回撥函數名稱
		 * @return void
		 */
		close: function(fn) {
			$('#ui-fs .ui-fs-all .ui-fs-allinner div.list').find("a").die("click");
			$('.talkPop').remove();
			$('#tsbox').remove();
			$('.mod-at-wrap').remove();
			jQuery('.boxy-modal-blackout').remove();
			var back ='';
			if("undefined" != typeof(fn)){
			 	back = fn;
			}else if("undefined" != typeof(this.callback)){
				back = this.callback;
			}
			if("function" == typeof(back)){
				back();
			}else{
				eval(back);
			}
		},
		/**
		 * 提示彈窗
		 * @param string data 資訊資料
		 * @param string title 標題資訊
		 * @param function callback 回撥函數
		 * @return void
		 */
		alert:function(data,title,callback){
			this.init(title,callback);
			this.setcontent('<div class="question">'+data+'</div>');
			this.center();
		},
		/**
		 * 顯示彈窗
		 * @param string content 資訊資料
		 * @param string title 標題資訊
		 * @param function callback 回撥函數
		 * @return void
		 */
		show:function(content,title,callback){
			this.init(title,callback);
			this.setcontent(content);
			this.center();
		},
		/**
		 * 載入彈窗API
		 * @param string requestUrl 請求地址
		 * @param string title 彈窗標題
		 * @param string callback 視窗關閉後的回撥事件
		 * @param object requestData requestData
		 * @param string type Ajax請求協議，默認為GET
		 * @return void
		 */
		load:function(requestUrl,title,callback,requestData,type) {
			this.init(title,callback);
			if("undefined" != typeof(type)) {
				var ajaxType = type;
			}else{
				var ajaxType = "GET";
			}
			this.setcontent('<div style="width:150px;height:70px;text-align:center"><div class="load">&nbsp;</div></div>');
			var obj = this;
			if("undefined" == requestData) {
				var requestData = {};
			}
			jQuery.ajax({url:requestUrl,
				type:ajaxType,
				data:requestData,
				cache:false,
				dataType:'html',
				success:function(html){
					obj.setcontent(html);
					obj.center();
				}
			});
		},	
		/**
		 * 彈窗定位
		 * @return void
		 */
		_viewport: function() {
			var d = document.documentElement, b = document.body, w = window;
			return jQuery.extend(
				jQuery.browser.msie ? { left: b.scrollLeft || d.scrollLeft, top: b.scrollTop || d.scrollTop } : { left: w.pageXOffset, top: w.pageYOffset },
				!ui.box._u(w.innerWidth) ? { width: w.innerWidth, height: w.innerHeight } : (!ui.box._u(d) && !ui.box._u(d.clientWidth) && d.clientWidth != 0 ? { width: d.clientWidth, height: d.clientHeight } : { width: b.clientWidth, height: b.clientHeight }) );
		},
		/**
		 * 驗證參數
		 * @return void
		 */
		_u: function() {
			for(var i = 0; i < arguments.length; i++) {
				if(typeof arguments[i] != 'undefined') return false;
			}
			return true;
		},
		/**
		 * 樣式覆蓋
		 * @return void
		 */
		_cssForOverlay: function() {
			if (ui.box.IE6) {
				return ui.box._viewport();
			} else {
				return {width: '100%', height: jQuery(document).height()};
			}
		},
		/**
		 * 中間定位
		 * @param string axis 橫向，縱向
		 * @return void
		 */
		center: function(axis) {
			var v = ui.box._viewport();
			var o =  [v.left, v.top];
			if (!axis || axis == 'x') this.centerAt(o[0] + v.width / 2 , null);
			if (!axis || axis == 'y') this.centerAt(null, o[1] + v.height / 2);
			return this;
		},
		/**
		 * 橫向移動
		 * @param integer x 數值
		 * @return void
		 */
		moveToX: function(x) {
			if (typeof x == 'number') $('#tsbox').css({left: x});
			else this.centerX();
			return this;
		},
		/**
		 * 縱向移動
		 * @param integer y 數值
		 * @return void
		 */
		moveToY: function(y) {
			if (typeof y == 'number') $('#tsbox').css({top: y});
			else this.centerY();
			return this;
		},      
		centerAt: function(x, y) {
			var s = this.getSize();
			if (typeof x == 'number') this.moveToX(x - s[0]/2 );
			if (typeof y == 'number') this.moveToY(y - s[1]/2 );
			return this;
		},
		centerAtX: function(x) {
			return this.centerAt(x, null);
		},
		centerAtY: function(y) {
			return this.centerAt(null, y);
		},
		getSize: function() {
			return [$('#tsbox').width(), $('#tsbox').height()];
		},
		getContent: function() {
			return $('#tsbox').find('.boxy-content');
		},
		getPosition: function() {
			var b = $('#tsbox');
			return [b.offsetLeft, b.offsetTop];
		},        
		getContentSize: function() {
			var c = this.getContent();
			return [c.width(), c.height()];
		},
		_getBoundsForResize: function(width, height) {
			var csize = this.getContentSize();
			var delta = [width - csize[0], height - csize[1]];
			var p = this.getPosition();
			return [Math.max(p[0] - delta[0] / 2, 0), Math.max(p[1] - delta[1] / 2, 0), width, height];
		}
	}
};