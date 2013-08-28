/**
 * 查詢使用者操作Js插件
 * @example
 * 使用demo:<input type="text" name='' value='' onclick="core.searchUser.init($(this),showUser)">
 * @author jason <yangjs@yeah.net>
 * @version TS3.0
 */
core.searchUser = {
		/**
		 * 給工廠呼叫的介面
		 * @param array attrs 配置陣列
		 * @return void
		 * @private
		 */
		_init: function(attrs) {
			if(attrs.length == 1) {
				return false;
			} 
			if(attrs.length == 6) {
				core.searchUser.init(attrs[1],attrs[2],attrs[3],attrs[4],attrs[5]);
			} else if(attrs.length == 5) {
				core.searchUser.init(attrs[1],attrs[2],attrs[3],attrs[4]);
			} else if(attrs.length == 4) {
				core.searchUser.init(attrs[1],attrs[2],attrs[3]);
			} else if(attrs.length == 3) {
				core.searchUser.init(attrs[1],attrs[2]);
			} else {
				core.searchUser.init(attrs[1]);
			}
		},
		/**
		 * 插件初始化
		 * @param object input 查詢物件域
		 * @param integer follow 查詢類型，全站或關注的人
		 * @param integer max 最大查詢人數
		 * @param function callback 回撥函數
		 * @param integer noself 是否排除自己
		 * @return void
		 */
		init: function(input, follow, max, callback, noself) {
			this.notuser = '',					// 上次查詢的無結果使用者
			this.olduser = '',					// 上次查詢的使用者名
			this.searchTime = 0, 				// 當前搜索使用者記錄的次數
			this.input = input,					// 查詢的物件域
			this.userserintval = '', 			// 查詢使用者的輪詢
			this.userList = '';					// 使用者列表顯示物件
			this.follow = "undefined" == typeof(follow) ? 1 : follow;
			this.max = "undefined" == typeof(max) ? 0: max;
			this.noself = "undefined" == typeof(noself) ? 1: noself;
			this.stoploop = 0;
			if("undefined" != typeof(callback)) {
				this.callback = callback;
			} else {
				this.callback = '';
			}
			var _this = this;
			// 繫結離開事件
			this.input.blur(function() {
				var hide = function() {
					_this._stopUser();
					_this._removeUserList();
				};
				setTimeout(hide, 150);
			});
			// 移除使用者列表
			this._removeUserList();
			// 啟動使用者查詢
			this._startUser();
		},
		/**
		 * 插入使用者資料
		 * @param integer uid 使用者ID
		 * @param string uname 使用者昵稱
		 * @param string email 使用者Email
		 * @return void
		 */
		insertUser: function(uid, uname) {
			if(uid == '0') {
				return false;
			}
			var oArgs = M.getEventArgs(this.input[0]);
			if("undefined" == typeof(this.input.prev())) {
				$('<input type="hidden" value="' + oArgs.uid + '" name="' + oArgs.name + '" id="search_uids" rel="uids">').insertBefore(this.input);
			} else {
				if(this.input.prev().attr('rel') != 'uids') {
					$('<input type="hidden" value="' + oArgs.uid + '" name="' + oArgs.name + '" id="search_uids" rel = "uids">').insertBefore(this.input);
				}
			}
			var uidsInput = this.input.prev();
			if(uidsInput.prev().attr('rel') != "userlist") {
				$('<ul class="user-list" rel = "userlist" ></ul>').insertBefore(uidsInput);
			}
			var dllist = uidsInput.prev();
			var uids = uidsInput.val();
			var regExp = new RegExp( "^" + uid + "$|^" + uid + ",|," + uid );
			if(uids.match(regExp)) {
				ui.error(L('PUBLIC_USER_ISNOT_TIPES'));
				return false;		
			} else {
				var init = 1;
				if(this.max > 0) {
					if(uids != '') {
						var _uids = uids.split(',');
						if(_uids.length >= this.max) {
							ui.error(L('PUBLIC_SELECT_USER_TIPES',{'user':this.max}));
							return false;
						}	
						if(_uids.length+1 >= this.max) {
							this.input.blur();
							this.input.hide();
							init = 0;
						}
					} else {
						if(this.max == 1) {
							this.input.blur();
							this.input.hide();
							init = 0;
						}
					}
				}
				var html = '<li><a class="ico-close right" href="javascript:;" onclick ="core.searchUser.removeUser('+uid+',this)"></a>\
						   <div class="content"><a href="javascript:;">'+uname+'</a></div></li>';//<span>('+email+')</span>
				dllist.append(html);	
				if(uids!='' && uids !='0') {
					uidsInput.val(uids + "," +uid);
				} else {
					uidsInput.val(uid);
				}
				this.input.val('');
				this.inputReset(1);
				return true;
			}
		}, 
		inputReset:function(init){
			this.olduser = '';
			this._stopUser();
			this._removeUserList();
			this.stoploop = 0;
			if(init == 1){
				this.init(this.input,this.follow,this.max,this.callback,this.noself);
			}
		},
		selectUser:function(){
			var curUser = this.userList.find('.mod-at-list').find('.current');
			if(curUser.length>0){
				//選人吧
				var uid = curUser.attr('uid');
				var uname = curUser.attr('uname');
				var email = curUser.attr('email');
				core.searchUser.insertUser(uid,uname,email);
			}else{
				return true;
			}
			return true;
		},
		/**
		 * 移除已選中的使用者
		 * @param integer uid 移除使用者ID
		 * @param object obj 事件觸發物件
		 * @return void
		 */
		removeUser: function(uid, obj)
		{
			// 獲取隱藏表單的值
			var hideInput = null;
			$(obj).parent().parent().parent().find('input').each(function(){
				if($(this).attr('rel') == 'uids'){
					hideInput = $(this);
				}
			});
			// 移除LI
			$(obj).parent().remove();
			// 設定新的表單物件
			if(hideInput == null){
				hideInput = this.input.prev();
			}
			// 獲取表單物件值 
			var uids = hideInput.val();
			var arr = uids.split(',');
			var val = new Array();
			for(var i in arr) {
				if(arr[i] != uid && arr[i] != '' && "string" == typeof(arr[i])) {
					val.push(arr[i]);
				}
			}
			hideInput.val(val.join(','));
			this.input.show();
			this._removeUserList();
		},
		_startUser:function(){
			var _this = this;
			var loopSearchUser = function(){

				if(_this.stoploop == 1){
					return true;
				}

				var searchUser = function(searchTime,user){
					 $.post(U('widget/SearchUser/search'),{key:user,follow:_this.follow,noself:_this.noself},function(msg){
						 	if(searchTime != _this.searchTime){	// 超時了
						 		return false;
						 	}
					 	 	if(msg.status==0 || msg.data == null || msg.data =='' || msg.data.length == 0 ){
					 	 		_this.notuser = user;
					 	 		_this.userList.find('.mod-at-list').html("<div class='mod-at-tips'>"+L('PUBLIC_SEARCH_USER_TIPES')+"</div>");
					 	 		return false;
					 	 	}else{
					 	 		if("function" == typeof(_this.callback)){
					 	 			_this.callback(msg.data);
					 	 			return false;
					 	 		}
					 	 		var data = msg.data;
					 	 		_this.notuser ='';
					 	 		if(data.length > 0){ //列表
					 	 			var html = '<ul class="at-user-list">';
					 	 			for(var i in data){
					 	 				var current = i==0 ? " class='current'" : '';
					 	 				html +='<li onclick ="core.searchUser.insertUser('+data[i].uid+',\''+data[i].uname+'\')"' //,\''+data[i].email+'\'
					 	 						+' uid ="'+data[i].uid+'" uname="'+data[i].uname+'"'+current+'>'//	email="'+data[i].email+'" 
					 	 						+'<div class="face"><img src="'+data[i].avatar_small+'" width="20px" height="20px" /></div>'
					 	 						+'<div class="content"><a href="javascript:void(0)">'+data[i].uname+'</a><span></span></div></li>';//<span>'+data[i].email+'</span>
					 	 			}
					 	 			html +='</ul>';
					 	 			_this.userList.find('.mod-at-list').html(html);
					 	 			_this.userList.find('.mod-at-list').find('li').hover(function(){
										$(this).addClass('hover')	
									},function(){
										$(this).removeClass('hover');
									});
					 	 			//TODO 方向鍵控制
					 	 			core.plugInit('bindkey',$(_this.userList.find('.mod-at-list')),'li','current','core.searchUser.selectUser()');
					 	 		}else{	//直接添加
					 	 			core.searchUser.insertUser(data.uid,data.uname);//,data.email
					 	 			_this._removeUserList();
					 	 			//_this.input.parent().find('.mod-at-wrap').remove();
					 	 		}
					 	 	}
					},'json'); 
				};

				var user = _this.input.val();

				if(user == ''){  //重建顯示下拉框

					_this.olduser = '';
					//_this.input.parent().find('.mod-at-wrap').remove();
					//_this._removeUserList();
					core.searchUser._createUserlistDiv();
				}else{
					if((_this.notuser!='' && user.indexOf(_this.notuser) >= 0) || _this.olduser == user){
						//不查找使用者了
					}else{
						_this.olduser = user;
						_this.searchTime ++;
						core.searchUser._createUserlistDiv();
						searchUser(_this.searchTime,user);
					}
				}
			}
			this.userserintval = setInterval(loopSearchUser,250);
		},
		_stopUser:function(){
			this.stoploop = 1;
			if(this.userserintval != ''){
				//停止輪詢查找
				clearInterval(this.userserintval);
				this.userserintval='';
			}
			this._removeUserList();
			//this.input.parent().find('.mod-at-wrap').remove();
		},
		_createUserlistDiv:function(){

			if(typeof(this.userList)!='string'){
				return false;
			}

			var html = "<div class='mod-at-wrap' style='z-index:10000000;position:absolute;' id='message_box'><div class='mod-at'><div class='mod-at-list'>"
        			   +"<div class='mod-at-tips'>"+L('PUBLIC_PLEASE_SEARCH_USER')+"</div>"
            		   +"</div></div></div>";
            this.userList = $(html);
            this.userList.appendTo('body');
            var _this = this;
           	this._showUserList();
		},
		_showUserList:function(){
			var  x = this.input.offset();
			if(this.input[0].style.display == 'none'){
				this.userList.css({'left':x.left+'px','top':(x.top+this.input.height()+14)+'px','width':this.input.width()+10+'px','display':'none'});
			}else{
				this.userList.css({'left':x.left+'px','top':(x.top+this.input.height()+14)+'px','width':this.input.width()+10+'px','display':'block'});
			}
		},
		_removeUserList:function(){
			if($('#message_box').length >0 ){
				$('#message_box').remove();
			}

			if(this.userList.length > 0 && "string" != typeof(this.userList)){
				this.userList.remove();
			}
			this.userList  = '';
		}
	 };