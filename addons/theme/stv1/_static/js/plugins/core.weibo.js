// 微博核心Js操作
core.weibo = {
	_init:function(){
		return true;
	},
	// 微博初始化
	init:function(agrs) {
		this.initNums = agrs.initNums;		// 微博字數
		this.maxId = args.maxId,			// 最大微博ID
		this.loadId = args.loadId,			// 載入的微博ID
		this.feedType = args.feedType,		// 微博類型
		this.loadmore = args.loadmore,		// 是否載入更多
		this.uid = args.uid,				// 當前微博列表對應的UID
		this.loadnew = args.loadnew;		// 是否載入最新
		this.feed_type = args.feed_type;
		this.feed_key = args.feed_key;
		this.firstId = args.firstId;	
		this.topic_id = args.topic_id;		// 是否為話題
		this.gid = args.gid;
		//this.pre_page = "undefined" == typeof(pre_page) ? 1 :pre_page;//分頁用到的前一頁
		if("undefined" == typeof(this.loadCount)) {
			this.loadCount = 1;
		}
		if(this.loadmore == 1) {	
			this.canLoading = true;		// 當前是否允許載入
			core.weibo.bindScroll();
		} else {	
			this.canLoading = false;	// 當前是否允許載入
		}
		this.startNewLoop();
//		if($('#feed-lists').length > 0 && this.canLoading){
//			$('#feed-lists').append("<div class='loading' id='loadMore'>" + L('PUBLIC_LOADING') + "<img src='" + THEME_URL + "/image/load.gif' class='load'></div>");
//			core.weibo.loadMoreFeed();
//		}
	},
	// 頁底載入微博
	bindScroll: function() {	
		var _this = this;
		$(window).bind('scroll resize', function() {
			// 載入3次後，將不能自動載入微博
			if(_this.loadCount >= 4 || _this.canLoading == false){
				return false;
			}
			var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
			var bodyHeight = $(document.body).height();
			if(bodyTop + $(window).height() >= bodyHeight - 250) {
				_this.loadCount = _this.loadCount + 1;
				if($('#feed-lists').length > 0 && _this.loadId != null){
					$('#feed-lists').append("<div class='loading' id='loadMore'>" + L('PUBLIC_LOADING') + "<img src='" + THEME_URL + "/image/load.gif' class='load'></div>");
					core.weibo.loadMoreFeed();
				}
			}
		});
	},
	// 載入更多微博
	loadMoreFeed: function() {
		var _this = this;
		_this.canLoading = false;
		// 獲取微博資料
		$.get(U('widget/FeedList/loadMore'), {'loadId':_this.loadId, 'type':_this.feedType, 'uid':_this.uid, 'feed_type':_this.feed_type, 'feed_key':_this.feed_key, 'fgid':fgid, 'topic_id':_this.topic_id, 'load_count':_this.loadCount, 'gid':_this.gid}, function(msg) {
			// 載入失敗
			if(msg.status == "0" || msg.status == "-1") {
				$('#loadMore').remove();
				if(msg.status == 0 && ("undefined" != typeof(msg.msg)) && _this.loadmore > 0) {
					$('#feed-lists').append('<div class="loading" id="loadMore">' + msg.msg + '</div>');
				}
			}
			// 載入成功
			if(msg.status == "1") {
				if(msg.firstId > 0 && _this.loadnew == 1) {
					_this.firstId = msg.firstId;
					// 啟動查找最新的loop
//					_this.startNewLoop();
				}
				$('#loadMore').remove();
				if(_this.loadCount >= 4) {
					var $lastDl = $('<div></div>');
					$lastDl.html(msg.html);
					msg.html = $lastDl.find('dl').filter('.feed_list').slice(30);
				}
				$('#feed-lists').append(msg.html);
				_this.canLoading = true;
				_this.loadId = msg.loadId;
				if(_this.loadCount >= 4) {
					$('#feed-lists').append('<div id="page" class="page" style="display:none;">' + msg.pageHtml + '</div>');
					if($('#feed-lists .page').find('a').size() > 2) {
						// 4ping + next 說明還有30個以上
						var href = false;
						$('#feed-lists .page').find('a').each(function() {
							href = $(this).attr('href');
						});
						// 重組分頁結構
						$('#feed-lists .page').html(msg.pageHtml).show();
						$('#feed-lists .page').find('a').each(function() {
							var href = $(this).attr('href');
							if(href) {
								$(this).attr('href', 'javascript:;');
								$(this).click(function() {
									core.weibo.loadMoreByPage(href);
								});
							}
						});
					} else {
						if($('#feed-lists').find('dl').size() > 0) {
							$('#feed-lists').append('<div class="loading" id="loadMore">' + L('PUBLIC_ISNULL') + '</div>');
						}
					}
				} else {
					core.weibo.bindScroll();
				}
				M(document.getElementById('feed-lists'));
			}
		}, 'json')
		return false;
	},
	// 分頁載入更多資料
	loadMoreByPage: function(href) {
		var obj = this;
		obj.canLoading = false;
		$('#feed-lists').html("<div class='loading' id='loadMore'>"+L('PUBLIC_LOADING')+"<img src='"+THEME_URL+"/image/load.gif' class='load'></div>");
		scrolltotop.scrollup();
		$.get(href,{},function(msg){
			if(msg.status == "0" || msg.status == "-1"){
				$('#feed-lists').append("<div class='load' id='loadMore'>'+L('PUBLIC_ISNULL')+'</div>");
			}else{
				$('#feed-lists').html(msg.html);
				$('#feed-lists').append('<div id="page" class="page" >'+msg.pageHtml+'</div>');
				
				$('#feed-lists .page').find('a').each(function(){
					var href = $(this).attr('href');
					if(href){
						$(this).attr('href','javascript:void(0);');
						$(this).click(function(){
							core.weibo.loadMoreByPage(href);
						});
					}
				});
				//core.weibo.bindScroll();
				M(document.getElementById('feed-lists'));
			}
		},'json');
		return false;
	},
	// 載入最新微博
	startNewLoop: function() {
		var _this = this;
		var searchNew = function() {
			if(_this.firstId < 1) {
				return false;
			}
			// 載入最新的資料
			$.post(U('widget/FeedList/loadNew'), {maxId:_this.firstId, type:'new' + _this.feedType, uid:_this.uid}, function(msg) {
				if(msg.status == 1 && msg.count > 0) {
					_this.showNew(msg.count);
					_this.tempHtml = msg.html;
					_this.tmpfirstId = msg.maxId;
				}
			}, 'json');
		};
		// 每2分鐘查找一次最新微博
		var loop = setInterval(searchNew, 120000);
	},
	// 提示有多少新微博資料
	showNew: function(nums) {
		if($('#feed-lists').find('.notes').length > 0) {
			$('#feed-lists').find('.notes').html(L('PUBLIC_WEIBO_NUM',{'sum':nums}));
		} else {
			var html = '<a href="javascript:core.weibo.showNewList()" class="notes">'+L('PUBLIC_WEIBO_NUM',{'sum':nums})+'</a>';
			$('#feed-lists').prepend(html);	
		}
	},
	showNewList:function(){
		$('#feed-lists').find('.notes').remove();
		$('#feed-lists').prepend(this.tempHtml);
		this.firstId  = this.tmpfirstId;
		this.tempHtml = '';
		M(document.getElementById('feed-lists'));
	},
	// 釋出微博之後操作
	afterPost: function(obj, textarea, topicHtml, close) {
		if(topicHtml == ''){
			textarea.value = '';
		}else{
			textarea.value = topicHtml;
		}
		
		obj.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = L('PUBLIC_INPUT_TIPES',{'sum':'<span>'+initNums+'</span>'});
		var fadeOutObj = function() {
			textarea.ready = null;	
		};
		
		$(obj.childModels['post_ok'][0]).fadeOut(500,fadeOutObj);
		// 修改微博數目
		if("undefined" == typeof(close) || !close) {
			updateUserData('weibo_count',1);
		}
		if("undefined" != typeof(core.uploadFile)) {
			core.uploadFile.removeParentDiv();
		}
		if("undefined" != typeof(core.contribute)) {
			core.contribute.resetBtn();
		}
	},
	// 將json資料插入到feed-lists中
	insertToList: function(html, feedId) {
		//alert(html);exit;
		if("undefined" == typeof(html) || html == '') {
			return false;
		}
		//alert(123);exit;
		if($('#feed-lists').length > 0) {
			var before = $('#feed-lists dl').eq(0);
			$dl = $('<dl></dl>');
			$dl.attr('model-node', 'feed_list');
			$dl.attr('id', 'feed'+feedId);
			$dl.addClass('feed_list');
			$dl.html(html);
			if(before.length > 0) {
				$dl.insertBefore(before);
			} else {
				if($('#feed-lists').find('dl').size() > 0) {
					$('#feed-lists').append($dl);
				} else {
					$('#feed-lists').html($dl);
				}
			}
			M($dl[0]);
		}
		//DIY專用
		if($('#feed-lists-d').length > 0) {
			var before = $('#feed-lists-d dl').eq(0);
			var _dl = document.createElement('dl');
			_dl.setAttribute('class', 'feed_list');
			_dl.setAttribute('model-node', 'feed_list');
			_dl.setAttribute('id', 'feed'+feedId);
			_dl.innerHTML = html;
			if(before.length > 0) {
				$(_dl).insertBefore(before);
			} else {
				if($('#feed-lists-d').find('dl').size() > 0) {
					$('#feed-lists-d').append(_dl);
				} else {
					$('#feed-lists-d').html(_dl);
				}
			}
			M(_dl);
		}
		if("undefined" != typeof(after_publish_weibo)) {
			after_publish_weibo(feedId);
		}
	},
	// 檢驗微博內容，obj = 要驗證的表單物件，post = 表示是否釋出
	checkNums: function(obj, post) {
		if("undefined" == typeof(obj.parentModel.parentModel.parentModel.childModels['numsLeft'])) {
			return true;
		}
		// 獲取輸入框中還能輸入的數字個數
		var strlen = core.getLength(obj.value , true);
		// 匹配尾部空白符
		if ($.trim(obj.value) !== '') {
			var blank = obj.value.match(/\s+$/g);
			if (blank !== null) {
				strlen += Math.ceil(blank[0].length / 2);
			}
		}
		var leftNums = initNums - strlen;
		if(leftNums == initNums && 'undefined' != typeof(post)) {
			return false;
		}
		// 獲取按鈕物件
		var objInput = '';
		if($(obj.parentModel.parentModel.childModels['send_action']).html() != null) {
			objInput = $(obj.parentModel.parentModel.childModels['send_action'][0]).find('a').eq(0);
		}
		// 獲取剩餘字數
		if(leftNums >= 0) {
			var html = (leftNums == initNums) ? L('PUBLIC_INPUT_TIPES', {'sum':'<span>'+leftNums+'</span>'}) : L('PUBLIC_PLEASE_INPUT_TIPES', {'sum':'<span>'+leftNums+'</span>'});
			obj.parentModel.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = html;
			$(obj).removeClass('fb');
			if(leftNums == initNums && $(obj).find('img').size() == 0) {
				if(typeof(objInput) == 'object') {
					objInput[0].className = 'btn-grey-white';
				}
				return false;	// 沒有輸入內容
			}
			if(typeof(objInput) == 'object') {
				objInput[0].className = 'btn-green-big';
			}
			return true;
		} else {
			var html = L('PUBLIC_INPUT_ERROR_TIPES', {'sum':'<span style="color:red">' + Math.abs(leftNums) + '</span>'});
			$(obj).addClass('fb');
			obj.parentModel.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = html;
			if(typeof(objInput) == 'object') {
				objInput[0].className = 'btn-grey-white';
			}
			return false;
		}
	},
	// 釋出微博
	post_feed: function(_this, mini_editor, textarea, isbox , url) {	
		var obj = this;
		// 避免重複發送
		if("undefined" == typeof(obj.isposting)) {
			obj.isposting = true;
		} else {
			if(obj.isposting == true) {
				return false;
			}
		}
		
		if("undefined" == typeof(isbox)) {
			isbox = false;
		}
		// 微博類型在此區分
		var args = $(_this).attr('event-args');
		var setargs = args.replace('type=postvideo','type=post');
		
		var attrs = M.getEventArgs(_this);
		var attachobj = $(_this.parentModel).find('.attach_ids');
		if(attachobj.length > 0) {
			var type = (attachobj.attr('feedtype') == 'image') ? 'postimage' : 'postfile';
			var attach_id = attachobj.val();
		} else {
			var attach_id = '';
			var type = attrs.type;
		}
		var videourl = $('#postvideourl').val();
		var app_name = attrs.app_name;
		if(obj.checkNums(textarea,'post') == false) {
			if(type == 'postimage') {
				textarea.value = L('PUBLIC_SHARE_IMAGES');
			} else if(type == 'postfile') {
				textarea.value = L('PUBLIC_SHARE_FILES');
			} else {
				flashTextarea(textarea);
				obj.isposting = false;
				return false;
			}
		}
		// 獲取投稿ID
		var channel_id = $.trim($('#contribute').val());
		// 為空處理
		var data = textarea.value;
		if(data == '' || data.length < 0) {
			// TODO 只有一次情況纔會執行到這裡面 一般是不會的
			ui.error( L('PUBLIC_CENTE_ISNULL') );
			obj.isposting = false;
			return false;
		}
		data = removeHTMLTag(data);
		if(data == '' || data.length < 0) {
			ui.error('請勿輸入非法與敏感字元');
			obj.isposting = false;
			return false;
		}
		if(!url){
			url = U('public/Feed/PostFeed');
		}
		//alert(url);exit;
		// 釋出微博
		$.post(url, {body:data, type:type, app_name:app_name, content:'', attach_id:attach_id,videourl:videourl, channel_id:channel_id, source_url:attrs.source_url, gid:attrs.gid}, function(msg) {
			obj.isposting = false;
			_this.className = 'btn-grey-white';
			$(_this).html('<span>' + L('PUBLIC_SHARE_BUTTON') + '</span>');
			if(msg.status == 1) {
				if("undefined" != typeof(core.uploadFile)) {
					core.uploadFile.clean();
				}
				var postOk = mini_editor.childModels['post_ok'][0];
				$(postOk).fadeIn('fast');
				core.weibo.afterPost(mini_editor,textarea,attrs.topicHtml);	
				if(!isbox) {
					core.weibo.insertToList(msg.data, msg.feedId);
				} else {
					ui.box.close();
					var mini = M.getModelArgs(mini_editor);
					ui.success(mini.prompt);
					if(document.getElementById('feed-lists') != null && channel_id == 0) {
						setTimeout(function() {
							core.weibo.insertToList(msg.data, msg.feedId);
						}, 1500);	
					}
				}
				M.setArgs(_this,setargs);
			} else {
				ui.error(msg.data);
			}
		}, 'json');
		return false;
	},
	friendlyDate: function(sTime, cTime)
	{
		var formatTime = function(num)
		{
			return (num < 10) ? '0' + num : num;
		};

		if(!sTime) {
			return '';
		}

		var cDate = new Date(cTime * 1000);
		var sDate = new Date(sTime * 1000);
		var dTime = cTime - sTime;
		var dDay = parseInt(cDate.getDate()) - parseInt(sDate.getDate());
		var dMonth = parseInt(cDate.getMonth() + 1) - parseInt(sDate.getMonth() + 1);
		var dYear = parseInt(cDate.getFullYear()) - parseInt(sDate.getFullYear());

		if(dTime < 60) {
			if(dTime < 10) {
				return '剛剛';
			} else {
				return parseInt(Math.floor(dTime / 10) * 10) + '秒前';
			}
		} else if(dTime < 3600) {
			return parseInt(Math.floor(dTime / 60)) + '分鐘前';
		} else if(dYear === 0 && dMonth === 0 && dDay === 0) {
			return '今天' + formatTime(sDate.getHours()) + ':' + formatTime(sDate.getMinutes());
		} else if(dYear === 0) {
			return formatTime(sDate.getMonth() + 1) + '月' + formatTime(sDate.getDate()) + '日 ' + formatTime(sDate.getHours()) + ':' + formatTime(sDate.getMinutes());
		} else {
			return sDate.getFullYear() + '-' + formatTime(sDate.getMonth() + 1) + '-' + formatTime(sDate.getDate()) + ' ' + formatTime(sDate.getHours()) + ':' + formatTime(sDate.getMinutes());
		}
	}
};