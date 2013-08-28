/**
 * 非同步提交表單
 * @param object form 表單DOM物件
 * @return void
 */
var ajaxSubmit = function(form) {
	var args = M.getModelArgs(form);
	M.getJS(THEME_URL + '/js/jquery.form.js', function() {
        var options = {
        	dataType: "json",
            success: function(txt) {
        		if(1 == txt.status) {
        			if("function" ===  typeof form.callback) {
        				form.callback(txt);
        			} else {
        				if("string" == typeof(args.callback)) {
        					eval(args.callback+'()');
        				} else {
        					ui.success(txt.info);
        				}
        			}
        		} else {
        			ui.error(txt.info);
        		}
            }
        };
        $(form).ajaxSubmit(options);
	});
};

(function(){
// 是否點選了發送按鈕
var isSubmit = 0;
// 塊狀模型監聽
M.addModelFns({
	account_save:{
		callback:function(){
			ui.success(L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
			setTimeout(function() {
				location.href = location.href;
			}, 1500);
		}
	},
	verify_apply:{
		callback:function(){
			ui.success('申請成功，請等待稽覈');
			setTimeout(function() {
				location.href = U('public/Account/authenticate');
			}, 1500);
		}
	},
	// 普通表單發送驗證
	normal_form: {
		submit: function() {
			isSubmit = 1;
			var oCollection = this.elements;
			var nL = oCollection.length;
			var bValid = true;
			var dFirstError;

			for(var i = 0; i < nL; i++) {
				var dInput = oCollection[i];
				var sName = dInput.name;
				// 如果沒有事件節點，則直接略過檢查
				if(!sName || !dInput.getAttribute("event-node")) {
					continue;
				}

				("function" === typeof(dInput.onblur)) && dInput.onblur();

				if(!dInput.bIsValid) {
					bValid = false;
					if(dInput.type != 'hidden') {
						dFirstError = dFirstError || dInput;
					}
				}
			}

			dFirstError && dFirstError.focus();
			setTimeout(function() {
				isSubmit = 0;
			}, 1500);

			return bValid;
		}
	}
});
// 事件模型監聽
M.addEventFns({
	// 文字框輸入文字驗證
	input_text: {
		focus: function() {
			this.className='s-txt-focus';
			return false;
		},
		blur: function() {
			this.className = 's-txt';
			// 設定文字框的最大與最小輸入限制
			var oArgs = M.getEventArgs( this );
			var	min = oArgs.min ? parseInt( oArgs.min ) : 0;
			var	max = oArgs.max ? parseInt( oArgs.max ) : 0;
			// 最大和最小長度均小於或等於0，則不進行長度驗證
			if(min <= 0 && max <= 0) {
				return false;
			}

			var dTips = (this.parentModel.childEvents[this.getAttribute( "name" ) + "_tips"] || [])[0];
			var sValue = this.value;
			sValue = sValue.replace(/(^\s*)|(\s*$)/g, "");	
			var nL = sValue.replace(/[^\x00-\xff]/ig,'xx').length / 2;

			if(nL <= min-1 || ( max && nL > max)) {
				dTips && (dTips.style.display = "none");
				tips.error(this, oArgs.error);
				this.bIsValid = false;
			} else {
				tips.success(this);
				dTips && (dTips.style.display = "");
				this.bIsValid = true;
			}
			return false;
		},
		load: function() {
			this.className='s-txt';
		}
	},
	// 文字框輸入純數字文字驗證
	input_nums: {
		focus: function() {
			this.className = 's-txt-focus';
			return false;
		},
		blur: function() {
			this.className = 's-txt';
			// 設定文字框的最大與最小輸入限制
			var oArgs = M.getEventArgs(this);
			var min = oArgs.min ? parseInt( oArgs.min ) : 0;
			var max = oArgs.max ? parseInt( oArgs.max ) : 0;
			// 最大和最小長度均小於或等於0，則不進行長度驗證
			if(min <= 0 && max <= 0) {
				return false;
			}

			var dTips = (this.parentModel.childEvents[this.getAttribute( "name" ) + "_tips"] || [])[0];
			var sValue = this.value;

			// 純數字驗證
			var re = /^[0-9]*$/;
			if(!re.test(sValue)) {
				dTips && (dTips.style.display = "none");
				tips.error(this, L('PUBLIC_TYPE_ISNOT'));		// 格式不正確
				this.bIsValid = false;
				return false;
			}

			sValue = sValue.replace(/(^\s*)|(\s*$)/g, "");	
			var nL = sValue.replace(/[^\x00-\xff]/ig, 'xx').length / 2;

			if(nL <= min-1 || (max && nL > max)) {
				dTips && (dTips.style.display = "none");
				tips.error(this, oArgs.error);
				this.bIsValid = false;
			} else {
				tips.success(this);
				dTips && (dTips.style.display = "");
				this.bIsValid = true;
			}

			return false;
		},
		load: function() {
			this.className = 's-txt';
		}
	},
	// 文字域驗證
	textarea: {
		focus: function() {
			this.className = 's-textarea-focus';
		},
		blur: function() {
			this.className = 's-textarea';
			// 設定文字框的最大與最小輸入限制
			var oArgs = M.getEventArgs(this);
			var min = oArgs.min ? parseInt( oArgs.min ) : 0;
			var max = oArgs.max ? parseInt( oArgs.max ) : 0;
			// 最大和最小長度均小於或等於0，則不進行長度驗證
			if(min <= 0 && max <= 0) {
				return false;
			}

			if($.trim(this.value)) {
				tips.success(this);
				this.bIsValid = true;
			} else {
				if("undefined" != typeof(oArgs.error )) {
					tips.error(this, oArgs.error);
					this.bIsValid = false;
				}
			}
		},
		load: function() {
			this.className = 's-textarea';
		}
	},
	// 部門資訊驗證
	input_department: {
		blur: function() {
			var sValue = this.value;
			sValue && (sValue = parseInt(sValue));

			var dLastEmlement = this.nextSibling;
			(1 !== dLastEmlement.nodeType) && (dLastEmlement = dLastEmlement.nextSibling);
			if(sValue) {
				tips.success(dLastEmlement);
				this.bIsValid = true;
			} else {
				tips.error(dLastEmlement, L('PUBLIC_SELECT_DEPARTMENT'));
				this.bIsValid = false;
			}
		},
		load:function(){
			if("undefined" != typeof(core.department)) {
				delete core.department;
			}
			core.plugInit('department', $(this), $(this));
		}
	},
	// 地區資訊驗證
	input_area: {
		blur: function() {
			// 獲取資料
			var sValue = $.trim(this.value);
			var sValueArr = sValue.split(",");
			// 驗證資料正確性
			if(sValue == "" || sValueArr[0] == 0) {
				tips.error(this, "請選擇地區");
				this.bIsValid = false;
				this.value = '0,0,0';
			} else if(sValueArr[1] == 0 || sValueArr[2] == 0) {
				tips.error(this, "請選擇完整地區資訊");
				this.bIsValid = false;
			} else {
				tips.success(this);
				this.bIsValid = true;
			}
		},
		load: function() {
			// 獲取參數資訊
			var _this = this;
			// 驗證資料正確性
			setInterval(function() {
				// 獲取資料
				var sValue = $.trim(_this.value);
				var sValueArr = sValue.split(",");
				// 驗證資料正確性
				if(sValue == "" || sValueArr[0] == 0) {
					tips.error(_this, "請選擇地區");
					_this.bIsValid = false;
				} else if(sValueArr[1] == 0 || sValueArr[2] == 0) {
					tips.error(_this, "請選擇完整地區資訊");
					_this.bIsValid = false;
				} else {
					tips.success(_this);
					_this.bIsValid = true;
				}
			}, 200);
		}
	},
	// 時間格式驗證
	input_date: {
		focus: function() {
			this.className = 's-txt-focus';

			var dDate = this;
			var oArgs = M.getEventArgs(this);

			M.getJS(THEME_URL + '/js/rcalendar.js', function() {
				rcalendar(dDate, oArgs.mode);
			});
		},
		blur: function() {
			this.className = 's-txt';

			var dTips = (this.parentModel.childEvents[this.getAttribute( "name" ) + "_tips"] || [])[0];
			var oArgs = M.getEventArgs(this);
			if(oArgs.min == 0) {
				return true;
			}		
			var _this = this;	
			setTimeout(function() {
				sValue = _this.value;
				if(!sValue) {
					dTips && (dTips.style.display = "none");
					tips.error(_this, oArgs.error);
					this.bIsValid = false;
				} else {
					tips.success(_this);
					dTips && (dTips.style.display = "");
					_this.bIsValid = true;
				}
			}, 250);
		},
		load: function() {
			this.className = 's-txt';
		}
	},
	// 郵箱驗證
	email: {
		focus: function() {
			this.className = 's-txt-focus';
			var x = $(this).offset();
			$(this.dTips).css({'position':'absolute','left':x.left+'px','top':x.top+$(this).height()+12+'px','width':$(this).width()+10+'px'});
		},
		blur: function() {
			this.className = 's-txt';

			var dEmail = this;
			var sUrl = dEmail.getAttribute("checkurl");
			var sValue = dEmail.value;

			if(!sUrl || (this.dSuggest && this.dSuggest.isEnter)) {
				return false;
			}

			$.post(sUrl, {email:sValue}, function(oTxt) {
				var oArgs = M.getEventArgs(dEmail);
				if(oTxt.status) {
					"false" == oArgs.success ? tips.clear( dEmail ) : tips.success( dEmail );
					dEmail.bIsValid = true;
				} else {
					"false" == oArgs.error ? tips.clear( dEmail ) : tips.error( dEmail, oTxt.info );
					dEmail.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		},
		load: function() {
			this.className = 's-txt';

			var dEmail = this;
			var oArgs = M.getEventArgs(this);

			if(!oArgs.suffix) {
				return false;
			}

			var aSuffix = oArgs.suffix.split( "," );
			var dFrag = document.createDocumentFragment();
			var dTips = document.createElement( "div" );
			var dUl = document.createElement( "ul" );
			
			this.dTips = $(dTips);
		    $('body').append(this.dTips);

		    dTips.className = "mod-at-wrap";
			dDiv = dTips.appendChild(dTips.cloneNode(false));
			dDiv.className = "mod-at";
			dDiv = dDiv.appendChild(dTips.cloneNode(false));
			dDiv.className = "mod-at-list";
			dUl = dDiv.appendChild(dUl);
			dUl.className = "at-user-list";
			dTips.style.display = "none";
			dEmail.parentNode.appendChild(dFrag);

			M.addListener(dTips, {
				mouseenter: function() {
					this.isEnter = 1;
				},
				mouseleave: function() {
					this.isEnter = 0;
				}
			});

			// 附加到Input DOM 上
			dEmail.dSuggest = dTips;

			setInterval(function() {
				var sValue = dEmail.value;
				var sTips = dEmail.dSuggest;
				if(dEmail.sCacheValue === sValue) {
					return false;
				} else {
					// 快取值
					dEmail.sCacheValue = sValue;
				}
				// 空值判斷
				if(!sValue) {
					dTips.style.display = "none";
					return ;
				}
				var aValue = sValue.split("@");
				var dFrag = document.createDocumentFragment();
				var l = aSuffix.length;
				var sSuffix;

				sInputSuffix = ["@",aValue[1]].join(""); // 使用者輸入的郵箱的字尾

				for(var i = 0; i < l; i ++) {
					sSuffix = aSuffix[i];
					if(aValue[1] && ( "" != aValue[1] ) && (sSuffix.indexOf(aValue[1]) !== 1 ) || (sSuffix === sInputSuffix)) {
						continue;
					}
					var dLi = dLi ? dLi.cloneNode(false) : document.createElement("li");
					var dA = dA ? dA.cloneNode(false) : document.createElement("a");
					var dSpan = dSpan ? dSpan.cloneNode(false) : document.createElement("span");
					var dText = dText ? dText.cloneNode(false) : document.createTextNode("");

					dText.nodeValue = [aValue[0], sSuffix].join("");

					dSpan.appendChild(dText);

					dA.appendChild(dSpan);

					dLi.appendChild(dA);

					dLi.onclick = (function(dInput, sValue, sSuffix) {
						return function(e) {
							dInput.value = [ sValue, sSuffix ].join( "" );
							// 選擇完畢，狀態為離開選擇下拉條
							dTips.isEnter = 0;
							// 自動驗證
							dInput.onblur();
							return false;
						};
					})(dEmail, aValue[0], sSuffix);

					dFrag.appendChild(dLi);
				}
				if(dLi) {
					dUl.innerHTML = "";
					dUl.appendChild( dFrag );
					dTips.style.display = "";
					$(dUl).find('li').hover(function() {
						$(this).addClass('hover');
					},function() {
						$(this).removeClass('hover');
					});

				} else {
					dTips.style.display = "none";
				}
			}, 200);
		}
	},
	// 密碼驗證
	password: {
		focus: function() {
			this.className = 's-txt-focus';
		},
		blur: function() {
			this.className = 's-txt';
			var dWeight = this.parentModel.childModels["password_weight"][0];
			var sValue = this.value + "";
			var nL = sValue.length;
			var min = 6
			var max = 15;
			if ( nL < min ) {
				dWeight.style.display = "none";
				tips.error( this, L('PUBLIC_PASSWORD_TIPES_MIN',{'sum':min}));
				this.bIsValid = false;
			} else if ( nL > max ) {
				dWeight.style.display = "none";
				tips.error( this, L('PUBLIC_PASSWORD_TIPES_MAX',{'sum':max}) );
				this.bIsValid = false;
			} else {
				tips.clear( this );
				dWeight.style.display = "";
				this.bIsValid = true;
				this.parentModel.childEvents["repassword"][0].onblur();
			}
		},
		keyup:function(){
			this.value = this.value.replace(/^\s+|\s+$/g,""); 
		},
		load: function() {
			this.value = '';
			this.className='s-txt';

			var dPwd = this,
				dWeight = this.parentModel.childModels["password_weight"][0],
				aLevel = [ "psw-state-empty", "psw-state-poor", "psw-state-normal", "psw-state-strong" ];

			setInterval( function() {
				var sValue = dPwd.value;
				// 快取值
				if ( dPwd.sCacheValue === sValue ) {
					return ;
				} else {
					dPwd.sCacheValue = sValue;
				}
				// 空值判斷
				if ( ! sValue ) {
					dWeight.className = aLevel[0];
					dWeight.setAttribute('className',aLevel[0]);
					return ;
				}
				var nL = sValue.length;

				if ( nL < 6 ) {
					dWeight.className = aLevel[0];
					dWeight.setAttribute('className',aLevel[0]);
					return ;
				}

				var nLFactor = Math.floor( nL / 10 ) ? 1 : 0;
				var nMixFactor = 0;

				sValue.match( /[a-zA-Z]+/ ) && nMixFactor ++;
				sValue.match( /[0-9]+/ ) && nMixFactor ++;
				sValue.match( /[^a-zA-Z0-9]+/ ) && nMixFactor ++;
				nMixFactor > 1 && nMixFactor --;

				dWeight.className = aLevel[nLFactor + nMixFactor];
				dWeight.setAttribute('className',aLevel[nLFactor + nMixFactor]);

			}, 200 );
		}
	},
	repassword: {
		focus: function() {
			this.className='s-txt-focus';
		},
		keyup:function(){
			this.value = this.value.replace(/^\s+|\s+$/g,""); 
		},
		blur: function() {
			this.className='s-txt';

			var sPwd = this.parentModel.childEvents["password"][0].value,
				sRePwd = this.value;

			if ( ! sRePwd ) {
				tips.error( this, L('PUBLIC_PLEASE_PASSWORD_ON') );
				this.bIsValid = false;
			} else if ( sPwd !== sRePwd ) {
				tips.error( this, L('PUBLIC_PASSWORD_ISDUBLE_NOT') );
				this.bIsValid = false;
			} else {
				tips.success( this );
				this.bIsValid = true;
			}
		},
		load: function() {
			this.className='s-txt';
		}
	},
	// 昵稱驗證
	uname: {
		focus: function() {
			this.className='s-txt-focus';
			return false;
		},
		blur: function() {
			this.className='s-txt';

			var dUname = this;
			var sUrl = dUname.getAttribute('checkurl');
			var sValue = dUname.value;
			var oArgs = M.getEventArgs(dUname);
			var oValue = oArgs.old_name;

			if(!sUrl || (this.dSuggest && this.dSuggest.isEnter)) return;

			$.post(sUrl, {uname:sValue, old_name:oValue}, function(oTxt) {
				if(oTxt.status) {
					'false' == oArgs.success ? tips.clear(dUname) : tips.success(dUname);
					dUname.bIsValid = true;
				} else {
					'false' == oArgs.error ? tips.clear(dUname) : tips.error(dUname, oTxt.info);
					dUname.bIsValid = false;
				}
				return true;
			}, 'json');
			$(this.dTips).hide();
		},
		load: function() {
			this.className='s-txt';
		}
	},
	radio: {
		click: function() {
			this.onblur();
		},
		blur: function() {
			var sName  = this.name,
				oRadio = this.parentModel.elements["sex"],
				oArgs  = M.getEventArgs( oRadio[0] ),
				dRadio, nL = oRadio.length, bIsValid = false,
				dLastRadio = oRadio[nL - 1];

			for ( var i = 0; i < nL; i ++ ) {
				dRadio = oRadio[i];
				if ( dRadio.checked ) {
					bIsValid = true;
					break;
				}
			}

			if ( bIsValid ) {
				tips.clear( dLastRadio.parentNode );
			} else {
				tips.error( dLastRadio.parentNode, oArgs.error );
			}

			for ( var i = 0; i < nL; i ++ ) {
				oRadio[i].bIsValid = bIsValid;
			}
		}
	},
	checkbox: {
		click: function() {
			this.onblur();
		},
		blur: function() {
			var oArgs = M.getEventArgs( this );
			if ( this.checked ) {
				tips.clear( this.parentNode );
				this.bIsValid = true;
			} else {
				tips.error( this.parentNode, oArgs.error );
				this.bIsValid = false;
			}
		}
	},
	submit_btn: {
		click: function(){
			var args  = M.getEventArgs(this);
			if ( args.info && ! confirm( args.info )) {
				return false;
			}
			try{
				(function( node ) {
					var parent = node.parentNode;
					// 判斷node 類型，防止意外迴圈
					if ( "FORM" === parent.nodeName ) {
						if ( "false" === args.ajax ) {
							( ( "function" !== typeof parent.onsubmit ) || ( false !== parent.onsubmit() ) ) && parent.submit();
						} else {
							ajaxSubmit( parent );
						}
					} else if ( 1 === parent.nodeType ) {
						arguments.callee( parent );
					}
				})(this);
			}catch(e){
				return true;
			}
			return false;
		}
	},
	sendBtn: {
		click: function() {
			var parent = this.parentModel;
			return false;
		}
	}
});
/**
 * 提示語Js物件
 */
var tips = {
	/**
	 * 初始化，正確與錯誤提示
	 * @param object D DOM物件
	 * @return void
	 */
	init: function(D) {
		this._initError(D);
		this._initSuccess(D);
	},
	/**
	 * 呼叫錯誤介面
	 * @param object D DOM物件
	 * @param string txt 顯示內容
	 * @return void
	 */
	error: function(D, txt) {
		this.init(D);
		if($(D).val() == '' && isSubmit != 1) {
			D.dError.style.display = "none";
			D.dSuccess.style.display = "none";
		} else {
			D.dSuccess.style.display = "none";
			D.dError.style.display = "";
			D.dErrorText.nodeValue = txt;
		}
	},
	/**
	 * 呼叫成功介面
	 * @param object D DOM物件
	 * @return void
	 */
	success: function(D) {
		this.init(D);
		D.dError.style.display = "none";
		D.dSuccess.style.display = "";
	},
	/**
	 * 清除提示介面
	 * @param object D DOM物件
	 * @return void
	 */
	clear: function(D) {
		this.init(D);
		D.dError.style.display = "none";
		D.dSuccess.style.display = "none";
	},
	/**
	 * 初始化錯誤物件
	 * @param object D DOM物件
	 * @return void
	 * @private
	 */
	_initError: function(D) {
		if (!D.dError || !D.dErrorText) {
			// 創建DOM結構
			var dFrag = document.createDocumentFragment();
			var dText = document.createTextNode("");
			var dB = document.createElement("b");
			var dSpan = document.createElement("span");
			var dDiv = document.createElement("div");
			// 組裝HTML結構 - DIV
			D.dError = dFrag.appendChild(dDiv);
			dDiv.className = "box-ver";
			dDiv.style.display = "none";
			// 組裝HTML結構 - SPAN
			dDiv.appendChild( dSpan );
			// 組裝HTML結構 - B
			dSpan.appendChild( dB );
			dB.className = "ico-error";
			D.dErrorText = dSpan.appendChild(dText);
			// 插入HTML
			var dParent = D.parentNode;
			var dNext = D.nextSibling;
			if(dNext) {
				dParent.insertBefore(dFrag, dNext);
			} else {
				dParent.appendChild(dFrag);
			}
		}
	},
	/**
	 * 初始化成功物件
	 * @param object D DOM物件
	 * @return void
	 * @private
	 */
	_initSuccess: function(D) {
		if(!D.dSuccess) {
			// 創建DOM結構
			var dFrag = document.createDocumentFragment();
			var dSpan = document.createElement("span");
			// 組裝HTML結構 - SPAN
			D.dSuccess = dFrag.appendChild(dSpan);
			dSpan.className = "ico-ok";
			dSpan.style.display = "none";
			// 插入HTML
			var dParent = D.parentNode;
			var dNext = D.nextSibling;
			if(dNext) {
				dParent.insertBefore(dFrag, dNext);
			} else {
				dParent.appendChild(dFrag);
			}
		}
	}
};
// 定義Window屬性
window.tips = tips;
})();