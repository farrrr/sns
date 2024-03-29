/**
 * 核心Js函數庫檔案，目前已經在core中自動載入
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */

// 字元串長度 - 中文和全形符號為1；英文、數字和半形為0.5
var getLength = function(str, shortUrl) {
	if (true == shortUrl) {
		// 一個URL當作十個字長度計算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
							.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};

// 擷取字元串
var subStr = function(str, len) {
    if(!str) {
    	return '';
    }
    len = len > 0 ? len * 2 : 280;
    var count = 0;			// 計數：中文2位元組，英文1位元組
	var temp = '';  		// 臨時字元串
    for(var i = 0; i < str.length; i ++) {
    	if(str.charCodeAt(i) > 255) {
        	count += 2;
        } else {
        	count ++;
        }
        // 如果增加計數後長度大於限定長度，就直接返回臨時字元串
        if(count > len) {
        	return temp;
        }
        // 將當前內容加到臨時字元串
		temp += str.charAt(i);
    }

    return str;
};
// 非同步請求頁面
var async_page = function(url, target, callback) {
	if(!url) {
		return false;
	} else if(target) {
		var $target = $(target);
		//$target.html('<img src="'+_THEME_+'/images/icon_waiting.gif" width="20" style="margin:10px 50%;" />');
	}
	$.post(url, {}, function(txt) {
		txt = eval("(" + txt + ")");
		if(txt.status) {
			if(target) {
				$target.html(txt.data);
			}
			if(callback) {
				if(callback.match(/[(][^()]*[)]/)) {
					eval(callback);
				} else {
					eval(callback)(txt);
				}
			}
			if(txt.info) {
				ui.success(txt.info);
			}
		} else if(txt.info) {
			ui.error(txt.info);
			return false;
		}
	});

	return true;
};
// 非同步載入翻頁
var async_turn_page = function(page_number, target) {
	$(page_number).click(function(o) {
		var $a = $(o.target);
		var url = $a.attr("href");
		if(url) {
			async_page(url, target);
		}
		return false;
	});
};

//表單非同步處理 
/* 生效條件：包含 jquery.form.js */
//TODO 優化jquery.form.js的載入機制
var async_form = function(form)
{
	var $form = form ? $(form) : $("form[ajax='ajax']");

	//監聽 form 表單提交
	$form.bind('submit', function() {
		var callback = $(this).attr('callback');
		var options = {
		    success: function(txt) {
		    	txt = eval("("+txt+")");
				if(callback){
					if (callback.match(/[(][^()]*[)]/)) {
						eval(callback);
					} else {
						eval(callback)(txt);
					}
				}else{
					if(txt.status && txt.info){
						ui.success( txt.info );
					}else if (txt.info) {
						ui.error( txt.info );
					}						  	 
				}
		    }
		};		
    $(this).ajaxSubmit(options);
		return false;
});
};

// 複製剪貼簿
var copy_clip = function (copy){
	if (window.clipboardData) {
		 window.clipboardData.setData("Text", copy);
	 } else if (window.netscape) {
		  try {
			   netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		  } catch (e) {
			   alert( L('PUBLIC_EXPLORER_ISCTRL') );
			   return false;
		  }
		  var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		  if (!clip) {
			  return false;
		  }
		  var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		  if (!trans) {
			  return false;
		  }
		  trans.addDataFlavor('text/unicode');
		  var str = new Object();
		  var len = new Object();
		  var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		  var copytext = copy;
		  str.data = copytext;
		  trans.setTransferData("text/unicode",str,copytext.length*2);
		  var clipid = Components.interfaces.nsIClipboard;
		  if (!clip) {
			  return false;
		  }
		  clip.setData(trans,null,clipid.kGlobalClipboard);
	 }
	 ui.success( L('PUBLIC_EXPLORER_CTRL') );
	 return true;	 
};
	//是否含有某個樣式
	
function hasClass(ele,cls) {
	return $(ele).hasClass(cls);
	//return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
	//添加某個樣式
function addClass(ele,cls) {
	$(ele).addClass(cls);
	//if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
	//移除某個樣式
function removeClass(ele,cls) {
	$(ele).removeClass(cls);
	//if (hasClass(ele,cls)) {
	//	var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		//ele.className=ele.className.replace(reg,' ');
	//}
}

var toElement = function(){
	var div = document.createElement('div');
	return function(html){
		div.innerHTML = html;
		var el = div.childNodes[0];
		div.removeChild(el);
		return el;
	}
}();

/**
 *	與php的implode方法用法一樣
 *	@from php.js  
 */

var implode  = function (glue, pieces) {
    var i = '',
        retVal = '',        tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }    if (typeof(pieces) === 'object') {
        if (Object.prototype.toString.call(pieces) === '[object Array]') {
            return pieces.join(glue);
        } 
        for (i in pieces) {            retVal += tGlue + pieces[i];
            tGlue = glue;
        }
        return retVal;
    }    return pieces;
};
/**
 * 與php的explode用法一致
 * @from php.js
 */
var explode = function(delimiter, string, limit){
	var emptyArray = {0:''};
 
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {        
    	return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;    
   }
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
};
/**
 *	與php的strlen方法用法一樣
 *	@from php.js  
 */
var strlen = function (string) {
    var str = string + '';
    var i = 0,        chr = '',
        lgth = 0;
 
    if (!this.php_js || !this.php_js.ini || !this.php_js.ini['unicode.semantics'] || this.php_js.ini['unicode.semantics'].local_value.toLowerCase() !== 'on') {
        return string.length;    }
 
    var getWholeChar = function (str, i) {
        var code = str.charCodeAt(i);
        var next = '',            prev = '';
        if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
            if (str.length <= (i + 1)) {
                throw 'High surrogate without following low surrogate';
            }            next = str.charCodeAt(i + 1);
            if (0xDC00 > next || next > 0xDFFF) {
                throw 'High surrogate without following low surrogate';
            }
            return str.charAt(i) + str.charAt(i + 1);        } else if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
            if (i === 0) {
                throw 'Low surrogate without preceding high surrogate';
            }
            prev = str.charCodeAt(i - 1);            if (0xD800 > prev || prev > 0xDBFF) { //(could change last hex to 0xDB7F to treat high private surrogates as single characters)
                throw 'Low surrogate without preceding high surrogate';
            }
            return false; // We can pass over low surrogates now as the second component in a pair which we have already processed
        }        return str.charAt(i);
    };
 
    for (i = 0, lgth = 0; i < str.length; i++) {
        if ((chr = getWholeChar(str, i)) === false) {            continue;
        } // Adapt this line at the top of any loop, passing in the whole string and the current iteration and returning a variable to represent the individual character; purpose is to treat the first part of a surrogate pair as the whole character and then ignore the second part
        lgth++;
    }
    return lgth;
};

/**
 * 與PHP的substr一樣的用法、
 * @from php.js 
 */
var substr = function(str, start, len) {
    var i = 0,
        allBMP = true,
        es = 0,        el = 0,
        se = 0,
        ret = '';
    str += '';
    var end = str.length; 
    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT    
    switch ((this.php_js.ini['unicode.semantics'] && this.php_js.ini['unicode.semantics'].local_value.toLowerCase())) {
    case 'on':
        // Full-blown Unicode including non-Basic-Multilingual-Plane characters
        // strlen()
        for (i = 0; i < str.length; i++) {            if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
                allBMP = false;
                break;
            }
        } 
        if (!allBMP) {
            if (start < 0) {
                for (i = end - 1, es = (start += end); i >= es; i--) {
                    if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {                        start--;
                        es--;
                    }
                }
            } else {                var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
                while ((surrogatePairs.exec(str)) != null) {
                    var li = surrogatePairs.lastIndex;
                    if (li - 2 < start) {
                        start++;                    } else {
                        break;
                    }
                }
            } 
            if (start >= end || start < 0) {
                return false;
            }
            if (len < 0) {                for (i = end - 1, el = (end += len); i >= el; i--) {
                    if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
                        end--;
                        el--;
                    }                }
                if (start > end) {
                    return false;
                }
                return str.slice(start, end);            } else {
                se = start + len;
                for (i = start; i < se; i++) {
                    ret += str.charAt(i);
                    if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {                        se++; // Go one further, since one of the "characters" is part of a surrogate pair
                    }
                }
                return ret;
            }            break;
        }
        // Fall-through
    case 'off':
        // assumes there are no non-BMP characters;        //    if there may be such characters, then it is best to turn it on (critical in true XHTML/XML)
    default:
        if (start < 0) {
            start += end;
        }        end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);
    }
    return undefined; // Please Netbeans
};

var trim = function(str,charlist){
	  return str;
};
/**
 * 與php的rtrim函數用法一致
 * @from php.js
 */
var rtrim = function(str, charlist) {
return str;
};

/**
 * 與PHP的ltrim用法一致
 * @from php.js
 */
var ltrim = function(str, charlist) {
	return str;
};

/**
 * 閃動物件背景
 * @param obj
 * @returns
 * @author yangjs
 */
var flashTextarea = function(obj){
	var nums = 0;
	var flash = function(){
		if(nums > 3 ){
			return false;
		}
		if(hasClass(obj,'fb')){
			removeClass(obj,'fb');
		}else{
			addClass(obj,'fb')
		}
		setTimeout(flash, 300);
		nums ++;
	}
	flash();
	return false;
};
/**
 * 更新頁面上監聽的使用者統計數目
 * @example
 * updateUserData('favorite_count', 1); 表示當前使用者的收藏數+1
 * 頁面結構例子:<strong event-node ="favorite_count" event-args ="uid={$uid}">{$_userData.favorite_count|default=0}</strong>
 * @param string key 監聽的Key值
 * @param integer flag 改變的幅度值
 * @param integer uid 改變的使用者ID
 * @return boolean false
 */
var updateUserData = function(key, flag, uid)
{
	// 獲取所有Key監聽的物件
	var countObj = M.nodes.events[key];
	// 判斷資料類型
	if("undefined" === typeof countObj) {
		return false;
	}
	if("undefined" === typeof uid) {
		uid = UID;
	}
	// 修改數值
	for(var i in countObj) {
		var _wC = countObj[i];
		var args = M.getEventArgs(_wC);
		if(args.uid == uid) {
			_wC.innerHTML = parseInt(_wC.innerHTML, 10) + parseInt(flag, 10);
		}
	}

	return false;
};

/**
 * 滾動到頂端
 */
var scrolltotop={
	//startline: 滑鼠向下滾動了100px後出現#topcontrol
	//scrollto: 它的值可以是整數，也可以是一個id標記。若為整數（假設為n），則滑動到距離top的n畫素處；若為id標記，則滑動到該id標記所在的同等高處
	//scrollduration:滑動的速度
	//fadeduration:#topcontrol這個div的淡入淡出速度，第一個參數為淡入速度，第二個參數為淡出速度
	//controlHTML:控制向上滑動的html源碼，默認為<img src="up.png" style="width:48px; height:48px" />，可以自行更改。該處的html程式碼會被包含在一個id標記為#topcontrol的div中。
	//controlattrs:控制#topcontrol這個div距離右下角的畫素距離
	//anchorkeyword:滑動到的id標籤
	/*state: isvisible:是否#topcontrol這個div為可見
			shouldvisible:是否#topcontrol這個div該出現
	*/

	setting: {startline:100, scrollto: 0, scrollduration:500, fadeduration:[500, 100]},
	controlHTML: '<a href="#top" class="top_stick">&nbsp;</a>',
	controlattrs: {offsetx:20, offsety:30},
	anchorkeyword: '#top',

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) {
			this.$control.css({opacity:0})
		};//點選後隱藏#topcontrol這個div
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto);
		if (typeof dest=="string" && jQuery('#'+dest).length==1) { //檢查若scrollto的值是一個id標記的話
			dest=jQuery('#'+dest).offset().top;
		} else { //檢查若scrollto的值是一個整數
			dest=this.setting.scrollto;
		};
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		//獲得瀏覽器的視窗物件
		var $window=jQuery(window);
		//獲得#topcontrol這個div的x軸座標
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx;
		//獲得#topcontrol這個div的y軸座標
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety;
		//隨著滑動塊的滑動#topcontrol這個div跟隨著滑動
		this.$control.css({left:controlx+'px', top:controly+'px'});
	},

	togglecontrol:function(){
		//當前視窗的滑動塊的高度
		var scrolltop=jQuery(window).scrollTop();
		if (!this.cssfixedsupport) {
			this.keepfixed();
		};
		//若設定了startline這個參數，則shouldvisible為true
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false;
		//若shouldvisible為true，且!isvisible為true
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0]);
			this.state.isvisible=true;
		} //若shouldvisible為false，且isvisible為false
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1]);
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop;
			var iebrws=document.all;
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest; //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body');

			//包含#topcontrol這個div
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
				.attr({title:L('PUBLIC_MOVE_TOP')})
				.click(function(){mainobj.scrollup(); return false;})
				.appendTo('body');

			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') {//loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}); //IE6- seems to require an explicit width on a DIV containing text
			};

			mainobj.togglecontrol();

			//點選控制
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup();
				return false;
			});

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			});
		});
	}
};
scrolltotop.init();
// JavaScript雙語方法
function L(key, obj) {
	if('undefined' == typeof(LANG[key])) {
		return key;
	}
	if('object' != typeof(obj)) {
		return LANG[key];
	} else {
		var r = LANG[key];
		for(var i in obj) {
			r = r.replace("{"+i+"}", obj[i]);
		}
		return r;
	}
};
/**
 * 陣列去重
 * @param array arr 去重陣列
 * @return array 已去重的陣列
 */
var unique = function(arr)
{
	var obj = {};
	for(var i = 0, j = arr.length; i < j; i++) {
		obj[arr[i]] = true;
	}
	var data = [];
	for(var i in obj) {
		data.push[i];
	}

	return data;
};
var shortcut = function (shortcut,callback,opt) {
	//Provide a set of default options
	var default_options = {
		'type':'keydown',
		'propagate':false,
		'target':document
	}
	if(!opt) opt = default_options;
	else {
		for(var dfo in default_options) {
			if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
		}
	}

	var ele = opt.target
	if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
	var ths = this;

	//The function to be called at keypress
	var func = function(e) {
		e = e || window.event;

		//Find Which key is pressed
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code).toLowerCase();

		var keys = shortcut.toLowerCase().split("+");
		//Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
		var kp = 0;
		
		//Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
		var shift_nums = {
			"`":"~",
			"1":"!",
			"2":"@",
			"3":"#",
			"4":"$",
			"5":"%",
			"6":"^",
			"7":"&",
			"8":"*",
			"9":"(",
			"0":")",
			"-":"_",
			"=":"+",
			";":":",
			"'":"\"",
			",":"<",
			".":">",
			"/":"?",
			"\\":"|"
		}
		//Special Keys - and their codes
		var special_keys = {
			'esc':27,
			'escape':27,
			'tab':9,
			'space':32,
			'return':13,
			'enter':13,
			'backspace':8,

			'scrolllock':145,
			'scroll_lock':145,
			'scroll':145,
			'capslock':20,
			'caps_lock':20,
			'caps':20,
			'numlock':144,
			'num_lock':144,
			'num':144,
			
			'pause':19,
			'break':19,
			
			'insert':45,
			'home':36,
			'delete':46,
			'end':35,
			
			'pageup':33,
			'page_up':33,
			'pu':33,

			'pagedown':34,
			'page_down':34,
			'pd':34,

			'left':37,
			'up':38,
			'right':39,
			'down':40,

			'f1':112,
			'f2':113,
			'f3':114,
			'f4':115,
			'f5':116,
			'f6':117,
			'f7':118,
			'f8':119,
			'f9':120,
			'f10':121,
			'f11':122,
			'f12':123
		}


		for(var i=0; k=keys[i],i<keys.length; i++) {
			//Modifiers
			if(k == 'ctrl' || k == 'control') {
				if(e.ctrlKey) kp++;

			} else if(k ==  'shift') {
				if(e.shiftKey) kp++;

			} else if(k == 'alt') {
					if(e.altKey) kp++;

			} else if(k.length > 1) { //If it is a special key
				if(special_keys[k] == code) kp++;

			} else { //The special keys did not match
				if(character == k) kp++;
				else {
					if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
						character = shift_nums[character]; 
						if(character == k) kp++;
					}
				}
			}
		}

		if(kp == keys.length) {
			if (lock == 0) {
				lock = 1;
				setTimeout(function(){
					lock = 0;
				}, 1500);
			} else {
				return false;
			}
			callback(e);

			if(!opt['propagate']) { //Stop the event
				//e.cancelBubble is supported by IE - this will kill the bubbling process.
				e.cancelBubble = true;
				e.returnValue = false;

				//e.stopPropagation works only in Firefox.
				if (e.stopPropagation) {
					e.stopPropagation();
					e.preventDefault();
				}
				return false;
			}
		}
	}

	//Attach the function with the event
	var lock = 0;
	if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
	else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
	else ele['on'+opt['type']] = func;
};
var atWho = function (obj){
	obj.atWho("@",{
        tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}'><img src='${faceurl}'  height='20' width='20' /> ${name}</li>"
        ,callback:function(query,callback) {
        	if ( keyname.text=='' ){
        		$.ajax({
                    url:U('widget/SearchUser/searchAt')
                    ,type:'GET'
                    ,dataType: "json"
                    ,success:function(res) {
                    	if ( res.data == null ){
                    		$('#at-view').hide();
                    		return;
                    	} else {
    	                    datas = $.map(res.data,function(value,i){
    	                        return {'id':value.uid,'key':value.uname+":",'name':value.uname,'faceurl':value.avatar_small,'searchkey':value.search_key}
    	                        })
                    	}
                        callback(datas)
                    }
                })
        	} else {
        		$.ajax({
                    url:U('widget/SearchUser/search')
                    ,type:'GET'
                    ,data: "type=at&key="+keyname.text
                    ,dataType: "json"
                    ,success:function(res) {
                    	if ( res.data == null ){
                    		$('#at-view').hide();
                    		return;
                    	} else {
    	                    datas = $.map(res.data,function(value,i){
    	                        return {'id':value.uid,'key':value.uname+":",'name':value.uname,'faceurl':value.avatar_small,'searchkey':value.search_key}
    	                        })
                    	}
                        callback(datas)
                    }
                })
        	}
        }
        }).atWho("#",{
            tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}#'>${name}</li>"
                ,callback:function(query,callback) {
            		$.ajax({
                        url:U('widget/TopicList/searchTopic')
                        ,type:'GET'
                        ,data: "key="+keyname.text
                        ,dataType: "json"
                        ,success:function(res) {
                        	if ( res == null ){
                        		$('#at-view').hide();
                        		return;
                        	} else {
        	                    datas = $.map(res,function(value,i){
        	                        return {'id':value.topic_id,'name':value.topic_name,'searchkey':value.topic_name}
        	                        })
                        	}
                            callback(datas)
                        }
                    });
                }
         }).atWho("＃",{
             tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}＃'>${name}</li>"
                 ,callback:function(query,callback) {
             		$.ajax({
                         url:U('widget/TopicList/searchTopic')
                         ,type:'GET'
                         ,data: "key="+keyname.text
                         ,dataType: "json"
                         ,success:function(res) {
                         	if ( res == null ){
                         		$('#at-view').hide();
                         		return;
                         	} else {
         	                    datas = $.map(res,function(value,i){
         	                        return {'id':value.topic_id,'name':value.topic_name,'searchkey':value.topic_name}
         	                        })
                         	}
                             callback(datas)
                         }
                     });
                 }
          });
};
$(function(){
    $.fn.extend({
        inputToEnd: function(myValue){
            var $t=$(this)[0];
            if (document.selection) {
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            } else if ($t.selectionStart || $t.selectionStart == '0') {
                var startPos = $t.selectionStart;
                var endPos = $t.selectionEnd;
                var scrollTop = $t.scrollTop;
                $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                this.focus();
                $t.selectionStart = startPos + myValue.length;
                $t.selectionEnd = startPos + myValue.length;
                $t.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        },
        inputToDIV: function(myValue){
        	
            var obj=$(this)[0];

			obj.focus();

			var selection = window.getSelection ? window.getSelection() : document.selection;

			var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);

			if (!window.getSelection){
				
				var selection = window.getSelection ? window.getSelection() : document.selection;

				var range = selection.createRange ? selection.createRange() : selection.getRangeAt(0);

				range.pasteHTML(myValue);

				range.collapse(false);

				range.select();

			}else{

				range.collapse(false);

				var hasR = range.createContextualFragment(myValue);

				var hasR_lastChild = hasR.lastChild;

				while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") {

					var e = hasR_lastChild;

					hasR_lastChild = hasR_lastChild.previousSibling;

					hasR.removeChild(e)

				}                                

				range.insertNode(hasR);

				if (hasR_lastChild) {

					range.setEndAfter(hasR_lastChild);

					range.setStartAfter(hasR_lastChild);

				}

				selection.removeAllRanges();

				selection.addRange(range);

			}

        }
    });
});
/**
 * 去掉字元串中的HTML標籤
 * @param string str 需要處理的字元串
 * @return string 已去掉HTML的字元串
 */
var removeHTMLTag = function(str)
{
	str = str.replace(/<\/?[^>]*>/g,'');
	return str;
};
var quickLogin = function (){
	ui.box.load(U('public/Passport/quickLogin'),'快速登入');
};
/* 圖片切換 */
(function(){
var fSwitchPic = function( oPicSection, nInterval ) {
	try {
		this.dPicSection = "string" === typeof oPicSection ? document.getElementById( oPicSection ) : oPicSection;
		this.nInterval = nInterval > 0 ? nInterval : 2000;
		this.dPicList  = this.dPicSection.getElementsByTagName( "div" );
		this.nPicNum   = this.dPicList.length;
	} catch( e ) {
		return e;
	}
	this.nCurrentPic = this.nPicNum - 1;
	this.nNextPic = 0;
	this.fInitPicList();

	this.dPicNav = this.dPicSection.getElementsByTagName( "ul" )[0];
	this.fInitPicNav();

	clearTimeout( this.oTimer );
	this.fSwitch();
	this.fStart();
};

fSwitchPic.prototype = {
	constructor: fSwitchPic,
	fInitPicList: function() {
		var oSwitchPic = this;
		this.dPicSection.onmouseover = function() {
			oSwitchPic.fPause();
		};
		this.dPicSection.onmouseout  = function() {
			oSwitchPic.fGoon();
		};
	},
	fInitPicNav: function() {
		var oSwitchPic = this,
			sPicNav = '',
			nPicNum = this.nPicNum;

		for ( var i = 0; i < nPicNum; i ++ ) {
			sPicNav += '<li style="list-style-type:none;"><a href="javascript:;" target="_self">' + ( i + 1 ) + '</a></li>';
		}
		this.dPicNav.innerHTML = sPicNav;

		// 追加屬性和Event
		var dPicNavMenu = this.dPicNav.getElementsByTagName( "a" ),
		    nL = dPicNavMenu.length;

		while ( nL -- > 0 ) {
			dPicNavMenu[nL].nIndex = nL;
			dPicNavMenu[nL].onclick     = function() {
				oSwitchPic.fGoto( this.nIndex );
				return false;
			};
		}
		this.dPicNavMenu = dPicNavMenu;
	},
	fSwitch: function() {
		if ( this.nPicNum <= 1 ){
			return;
		}
		var nCurrentPic = this.nCurrentPic,
			nNextPic    = this.nNextPic;
		this.dPicList[nNextPic].style.display = "";
		this.dPicList[nCurrentPic].style.display = "none";

		this.dPicNavMenu[nNextPic].className = "sel";
		this.dPicNavMenu[nCurrentPic].className = "";

		this.nCurrentPic = nNextPic;
		this.nNextPic = ( nNextPic < this.nPicNum - 1 ) ? ( nNextPic + 1 ) : 0;
	},
	fStart: function() {
		var oSwitchPic = this;
		this.oTimer = setTimeout( function() {
			oSwitchPic.fSwitch();
			oSwitchPic.fStart();
		}, this.nInterval );
	},
	fPause: function() {
		clearTimeout( this.oTimer );
	},
	fGoon: function() {
		clearTimeout( this.oTimer );
		this.fStart();
	},
	fGoto: function( nIndex ) {
		var nIndex = parseInt( nIndex );
		if ( nIndex == this.nCurrentPic ) {
			return false;
		}
		
		clearTimeout( this.oTimer );
		this.nNextPic = nIndex;
		this.fSwitch();
	}
};

window.fSwitchPic = fSwitchPic;

})();

var switchVideo = function(id,type,host,flashvar){
	if( type == 'close' ){
		$("#video_mini_show_"+id).show();
		$("#video_content_"+id).html( '' );
		$("#video_show_"+id).hide();
	}else{
		$("#video_mini_show_"+id).hide();
		$("#video_content_"+id).html( showFlash(host,flashvar) );
		$("#video_show_"+id).show();
	}
}

//顯示視訊
var showFlash = function ( host, flashvar) {
	if(host=='youtube.com'){
		var flashHtml = '<iframe width="560" height="315"  src="http://www.youtube.com/embed/'+flashvar+'" frameborder="0" allowfullscreen></iframe>';
	}else{
	var flashHtml = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="430" height="400">'
        + '<param value="transparent" name="wmode"/>'
		+ '<param value="'+flashvar+'" name="movie" />'
		+ '<embed src="'+flashvar+'" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" width="525" height="420"></embed>'
		+ '</object>';
	}
	return flashHtml;
}

//過濾html標籤
function strip_tags (input, allowed) {    
allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}