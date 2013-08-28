/**
 * 	本js內為微博關的JS函數及監聽
 *	TODO 需要優化重構 重構方式參考 core.comment 和 core.searchUser
 */


if("undefined" == typeof(initNums)){
	var initNums = "140";
}
if("undefined" == typeof(maxId)){
	var maxId = 0;
}
if("undefined" == typeof(loadId)){
	var loadId = 0;
}
if("undefinde" == typeof(firstId)){
	var firstId = 0;
}
if("undefined" == typeof(feedType)){
	var feedType = 'following';	// 默認的微博類型(關注的)
}
if("undefined" == typeof(feed_type)){
	var feed_type ='';
}
if("undefined" == typeof(feed_key)){
	var feed_key = '';
}
if("undefined" == typeof(loadmore)){
	var loadmore = 0;
}
if("undefined" == typeof(loadnew)){
	var loadnew = 0;
}

if("undefinde" == typeof(fgid)){
	var fgid = '';
}

if("undefined" == typeof(topic_id)) {
	var topic_id = 0;
}

if("undefinde" == typeof(gid)){
	var gid = 0;
}
var _doc = document;
var feedbtnlock = 0;
var args = new Array();
args['initNums'] 	= initNums;
args['maxId']		= maxId;
args['loadId']		= loadId;
args['firstId']		= firstId;
args['feedType']   	= feedType;
args['loadmore']   	= loadmore;
args['loadnew']   	= loadnew;
args['uid']			= UID;
args['feed_type']   = feed_type;
args['feed_key']	= feed_key;
args['topic_id'] 	= topic_id;
args['gid'] 	= gid;

if("undefined" == typeof(core.weibo)){	//只init一次
	core.plugFunc('weibo',function(){
		core.weibo.init(args);	
	});
}
/**
 * 事件繫結器
 */
M.addEventFns({
	insert_face: {	//彈出插入表情框
		click: function(){
			// alert(1);
			var target = this.parentModel.parentModel.childModels["mini_editor"][0];
			var parentDiv = this.parentModel.childModels['faceDiv'][0];
			core.plugInit('face',this,$(target).find('textarea'),parentDiv);
		}
	},
	insert_video: {	//彈出插入視訊框
		click: function(){
			var target = this.parentModel.parentModel.childModels["mini_editor"][0];
			var postfeed = this.parentModel.childEvents[$(this).attr('rel')][0];
			core.plugInit('video',this,$(target).find('textarea'),postfeed);
		}
	},
	show_url_detail: {	//連結地址詳情顯示
		mouseover: function(){
			$(this).parent().find('.url-detail').show();
		},
		mouseout: function(){
			$(this).parent().find('.url-detail').hide();
		}
	},
	feed_tab_btn: {
		click:function(){
			if($(this).hasClass('arrow-nav-t')){
				$(this).removeClass('arrow-nav-t');
				$(this).addClass('arrow-nav-b');
				$('.mod-feed-tab').hide();
				$(this).attr('title',L('PUBLIC_OPEN'));
			}else{
				$(this).removeClass('arrow-nav-b');
				$(this).addClass('arrow-nav-t');
				$(this).attr('title',L('PUBLIC_PUT'));
				$('.mod-feed-tab').show();
			}
		}
	},
	post_feed:{	//釋出普通|圖片微博 
		click:function(){
			if (feedbtnlock == 0) {
				feedbtnlock = 1;
				setTimeout(function(){
					feedbtnlock = 0;
				}, 1500);
			} else {
				ui.error('正在釋出請勿重複點選！');
				return false;
			}
			if($('.upload_tips').length >0){
				ui.error( L('PUBLIC_ATTACH_UPLOADING_NOSENT') );
				return false;
			}
			var _this = this;
			var mini_editor = this.parentModel.parentModel.childModels['mini_editor'][0];			
			var textarea = $(mini_editor).find('textarea').get(0);
			core.weibo.post_feed(_this,mini_editor,textarea);
		}
	},
	post_feed_box:{
		click:function(){
			if (feedbtnlock == 0) {
				feedbtnlock = 1;
				setTimeout(function(){
					feedbtnlock = 0;
				}, 1500);
			} else {
				ui.error('正在釋出請勿重複點選！');
				return false;
			}
			var _this = this;
			var mini_editor = this.parentModel.parentModel.childModels['mini_editor'][0];			
			var textarea = $(mini_editor).find('textarea').get(0);
			core.weibo.post_feed(_this,mini_editor,textarea,true);
		}
	},
	post_submission_box:{
		click:function(){
			var _this = this;
			var mini_editor = this.parentModel.parentModel.childModels['mini_editor'][0];			
			var textarea = $(mini_editor).find('textarea').get(0);
			core.weibo.post_feed(_this,mini_editor,textarea,true,'submission');
		}
	},
	insert_at:{
		click:function(){
			var target = this.parentModel.parentModel.childModels['mini_editor'][0];
			core.plugInit('at',$(target).find('textarea'),this);
			setTimeout('core.at.insertAt()', 200);
		}
	},
	insert_topic: {
		click: function() {
			var text = '#請在這裡輸入自定義話題#';
			var patt   =   new   RegExp(text,"g");
			var target = this.parentModel.parentModel.childModels['mini_editor'][0];
			var textarea = $(target).find('textarea');
            textarea.inputToEnd(text);
            var textArea = textarea.get(0);
            result = patt.exec( textarea.val() );
            
            var end = patt.lastIndex-1 ;
            var start = patt.lastIndex - text.length +1;
            if (document.selection) { //IE
                 var rng = textArea.createTextRange();
                 rng.collapse(true);
                 rng.moveEnd("character",end)
                 rng.moveStart("character",start)
                 rng.select();
            }else if (textArea.selectionStart || (textArea.selectionStart == '0')) { // Mozilla/Netscape…
                textArea.selectionStart = start;
                textArea.selectionEnd = end;
            }
            core.weibo.checkNums(textArea);
			return false;
		}
	},
	/**
	 * 投稿功能
	 * @type {Object}
	 */
	insert_contribute: {
		click: function(){
			var target = this.parentModel.parentModel.childModels['mini_editor'][0];
			core.plugInit('contribute', $(target).find('textarea'), this);
		}
	},
	delFeed:{
		click:function(){
			var attrs = M.getEventArgs(this);

			var _this = this;
			var delFeed =  function(){
				$.post(U('public/Feed/removeFeed'),{feed_id:attrs.feed_id},function(msg){
					if(msg.status == 1){
						if($('#feed'+attrs.feed_id).length > 0){
							$('#feed'+attrs.feed_id).fadeOut();
						}else{
							$(_this.parentModel).fadeOut();
						}
						updateUserData('weibo_count',-1,attrs.uid);
						if(attrs.isrefresh == 1){    //在微博詳情頁刪除後跳轉到首頁
							window.location.href = SITE_URL;
						}
					}else{
						ui.error( L('PUBLIC_DELETE_ERROR') );
					}
				},'json');
			};
			ui.confirm(this,L('PUBLIC_DELETE_THISNEWS'),delFeed);
		}
	},
	denounce:{	//舉報 
		click:function(){
			var attrs = M.getEventArgs(this);
			core.plugInit('denouce',attrs.aid,attrs.type,attrs.uid);
		}	
	},
	img_small:{ //圖片顯示
		click:function(){
			$(this.parentModel).find('div').each(function(){
				if($(this).attr('rel') == 'small'){
					$(this).hide();
				}else if($(this).attr('rel') == 'big'){
					$(this).show();
				}
			});
		}
	},
	img_big:{
		click:function(){
			var _this =  this;
			$(this.parentModel).find('div').each(function(){
				if($(this).attr('rel') == 'small'){
					$(this).show();
					
					var Y1 =this.getBoundingClientRect().top;
					if(Y1 < 0){
						//點選大圖縮小時  定位
						var dl_id = $(_this.parentModel).attr('id');
						window.location.hash = "#"+dl_id;
						window.location=window.location;
					}	
									
				}else if($(this).attr('rel') == 'big'){
					$(this).hide();
				}
			});
		}
	},
	searchFeed:{
		click:function(){
			var feedkey = $(this).prev().val();
			var args = M.getEventArgs(this);
			var url = U(args.app+'/'+args.mod+'/'+args.act)+'&type='+args.type+'&feed_key='+feedkey;
			window.location.href = url; 
		}
	},
	addFollowgroup:{
		click:function(){
			$.post(U('widget/FollowGroup/checkGroup'),{},function(msg){
				if(msg.status == 0){
					ui.error(msg.data);
				}else{
					ui.box.load(U('widget/FollowGroup/addgroup'), L('PUBLIC_CREATE_GROUP'));
				}
			},'json');
			
		}
	},
	editFollowgroup:{
		click:function(){
			ui.box.load(U('widget/FollowGroup/editgroup'),L('PUBLIC_MANAGE_GROUP'));
		}
	},
	//載入帖子詳情
	loadPost:{
		click:function(){
			var _this = this;
			var attrs = M.getEventArgs(this);
			if($(_this).parent().parent().find('.feed_img_lists').css('display') == 'none'){
				$(_this).parent().parent().find('.feed_img_lists').before('<dl id="loading" class="comment"><div class="loading" style="z-index:99;">載入中<img src="'+THEME_URL+'/image/load.gif" class="load"></div></dl>');
			};
			$.post(U('widget/FeedList/getPostDetail'),{post_id:attrs.post_id},function(res){
				html = '';
				html += '<dl class="comment">';
				if(res == 0){
					html += '<dt class="arrow bgcolor_arrow"><em class="arrline">◆</em><span class="downline">◆</span>';
					html += '<dd>帖子不存在或已被刪除</dd>';
					html += '</dt>';
				}else{
					html += '<div><a href="javascript:void(0);" onclick="$(\'#post_'+attrs.feed_id+'_'+attrs.post_id+'\').slideToggle()" class="ico-pack-up mr10">收起</a><a target="_blank" href="'+res.post_url+'" class="ico-show-all">檢視原文</a></div>';
					html += '<div class="content clearfix weiba-detail"><h3>'+res.title+'</h3><p class="f9"><a class="date right">'+res.post_time+'</a><span class="mr15">樓主：'+res.author+'</span><span>來自<a target="_blank" href="'+res.weiba_url+'">'+res.from_weiba+'</a></span></p><div><p>'+res.content+'</p></div></div>'
					html += '<div><a href="javascript:void(0);" onclick="$(\'#post_'+attrs.feed_id+'_'+attrs.post_id+'\').slideToggle();window.location.hash=\'#feed'+attrs.feed_id+'\';window.location=window.location;" class="ico-pack-up mr10">收起</a><a target="_blank" href="'+res.post_url+'" class="ico-show-all">檢視原文</a></div>';
				}
				html += '</div>';
				$('#loading').remove();
				$(_this).parent().parent().find('.feed_img_lists').html(html).slideToggle();
			},'json');
		}	
	},
	show_admin: {
		// 是否顯示按鈕
		load: function() {
			var args = M.getEventArgs(this);
			if(args.feed_del == 1 || args.channel_recommend == 1) {
				$(this).css('display', 'block');
			}
		},
		// 顯示與隱藏微博操作彈窗
		click: function() {
			$('#weibo_admin_box').remove();
			var _this = this;
			var args = M.getEventArgs(this);
			$(M.getEvents('show_admin')).addClass('hover');
			$(this).removeClass('hover');
			var offset = $(this).offset();
			var html = '<div id="weibo_admin_box" class="layer-list" style="display:none;z-index:1;"><ul>';
			if(args.channel_recommend == 1) {
				var channel_id = args.channel_id || 0;
				var clear = args.clear || 0;
				html += '<li><a href="javascript:;" onclick="getAdminBox('+args.feed_id+', '+channel_id+', '+clear+');">推薦到頻道</a></li>';
			}
			if(args.vtask_recommend == 1) {
				html += '<li><a href="javascript:;" onclick="addToVtask('+args.feed_id+');">添加到事務</a></li>';  //添加到事務
			}
			if(args.feed_del == 1) {
				if(typeof args.isrefresh == 'undefined') {
					args.isrefresh = 0;
				}
				html += '<li><a href="javascript:;" event-node ="delFeed" event-args="feed_id='+args.feed_id+'&uid='+args.uid+'&isrefresh='+args.isrefresh+'">刪除</a></li>';
			}
			html += '</ul></div>';
			$('body').append(html);
			$('#weibo_admin_box').css({position:'absolute', top:offset.top + 20, left:offset.left - 50});
			$('#weibo_admin_box').show();
			$('body').bind('click', function(event) {
				if($(event.target).attr('event-node') != 'show_admin') {
					$('#weibo_admin_box').remove();
					$(_this).addClass('hover');
				}
			});
			M(document.getElementById('weibo_admin_box'));
		}
	},
	// 微博內容輸入框
	mini_editor_textarea: {
		click: function() {
			if(!this.ready) {
				this.ready = 1;
				var _this = this;
				var checknums = function() {
					core.weibo.checkNums(_this);
				};
				var t = setInterval(checknums, 250);
			}
		},
		focus:function (){
			if(!this.ready) {
				this.ready = 1;
				var _this = this;
				var checknums = function() {
					core.weibo.checkNums(_this);
				};
				var t = setInterval(checknums, 250);
			}
		},
		load: function() {
			var _this = this;
			var lock = 0;
			$(this).keydown(function(eventobj) {
				var eventobj = eventobj ? eventobj : window.event;
				if(eventobj.ctrlKey && eventobj.keyCode == 13) {
					eventobj.keyCode = 0;
					eventobj.returnValue = false;
					var args = M.getModelArgs(_this);
					if("undefined" != typeof(args.t)) {
						if (lock == 0) {
							lock = 1;
							setTimeout(function(){
								lock = 0;
							}, 1500);
						} else {
							return false;
						}
						switch ( args.t ){
							case 'comment':
								// 評論
								var c = this.parentModel.parentModel.childEvents['do_comment'][0]; 
								var attrs = M.getEventArgs(c);
								attrs.to_comment_id = $(c).attr('to_comment_id');
								attrs.to_uid = $(c).attr('to_uid');
								attrs.to_comment_uname = $(c).attr('to_comment_uname');
								attrs.addToEnd = $(c).attr('addToEnd');
								var comment_list = c.parentModel.parentModel;
								core.plugInit('comment',attrs,comment_list);
								var docomment = function(){
									core.comment.addComment(null,c);
								}
								setTimeout(docomment, 150);
								break;
							case 'feed':
								// 動態
								var postObj = this.parentModel.parentModel.childModels['send_action'][0].childEvents['post_feed'][0];
								core.weibo.post_feed(postObj,this.parentModel,_this);
								break;
							case 'repostweibo':
								obj = this.parentModel.parentModel;
								$(obj).find('a[event-node="post_share"]').click();
						}
					}
					return true;
				}
//				if($('#atUserList').length < 1){
//					switch(eventobj.keyCode){  
//				        case 1:  
//				        //case 38:  
//				        //case 269: //up  
////				        case 40:  
////				        case 2:  
//				        //case 270: //down  
//				       // case 13: //enter  
//			          	return false;
//			            break;  
//				    } 
//			    }
				return true;
			});
		}		
	}
	
}).addModelFns({
	mini_editor:{
		mouseenter:function(){
			addClass(this,'focus');
		},
		mouseleave:function(){
			removeClass(this,'focus');
		}
	},
	// 微博內容輸入框
	mini_editor_textarea: {	
	}
});
$(".feed_img_lists li a").css("opacity","1").mouseover(function(){
	$(this).animate({opacity:"1"},300)
	});
$(".feed_img_lists li a").mouseout(function(){
	$(this).animate({opacity:"1"},10)
});
var getAdminBox = function(feedId, channelId, clear)
{
	ui.box.load(U('channel/Manage/getAdminBox')+'&feed_id='+feedId+'&channel_id='+channelId+'&clear='+clear, '推薦到頻道');
};
/**
 * 添加微事務視窗
 * @param integer feedId 微博ID
 * @return void
 */
var addToVtask = function(feedId) {
	ui.box.load(U('vtask/Index/addToVtask') + '&feed_id=' + feedId, '添加到微事務');
};