/**
 * 分享Js模型插件
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
core.share = {
	_init: function(attrs){
			return true;			// 純粹載入JS
	},
	// 釋出分享
	post_share: function(_this, mini_editor, textarea) {
		var obj = this;
		if( this.checkNums(textarea,'post') == false){
			flashTextarea(textarea);
			return false;
		}
		var data = textarea.value;
		if(data == '' || data.length<0){
			ui.error( L('PUBLIC_CENTE_ISNULL') );
			return false;
		}
		// 獲取評論checkbox值
		var comment_input = $(_this.parentModel).find('input');

		if( comment_input.attr('checked') == 'checked' ){
			var ifcomment = 1;
		}else{
			var ifcomment = 0;
		}
		var attrs =M.getEventArgs(_this);
		$.post(U('public/Feed/shareFeed'),{body:data,type:attrs.type,app_name:attrs.app_name,sid:attrs.sid,content:'',comment:ifcomment,curid:attrs.curid,curtable:attrs.curtable},
			function(msg){
			if(msg.status == 1){
				updateUserData('weibo_count',1);
				if(MID == UID){
					core.weibo.insertToList(msg.data);
				}
				
				ui.success( L('PUBLIC_SHARE_SUCCESS') );
			}else{
				ui.error(msg.data);
			}
		},'json');
		ui.box.close();
		return false;
	},
	// 檢驗字數方法
	checkNums: function(obj, post) {
		if("undefined" == typeof(obj.parentModel.parentModel.parentModel.childModels['numsLeft'])) {
			return true;
		}
		var strlen = core.getLength(obj.value , true);
		var leftNums = initNums - strlen;
		if(leftNums == initNums && 'undefined' != typeof(post)) {
			return false;
		}
		// 獲取剩餘字數
		if(leftNums >= 0) {
			var html = leftNums== initNums ? L('PUBLIC_INPUT_TIPES',{'sum':'<span>'+leftNums+'</span>'}):L('PUBLIC_PLEASE_INPUT_TIPES',{'sum':'<span>'+leftNums+'</span>'});
			obj.parentModel.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = html;
			$(obj).removeClass('fb');
			if(leftNums == initNums && $(obj).find('img').size() == 0) {
				return false;	//沒有輸入內容
			}
			return true;
		} else {
			var html = L('PUBLIC_INPUT_ERROR_TIPES',{'sum':'<span style="color:red">'+Math.abs(leftNums)+'</span>'});
			$(obj).addClass('fb');
			obj.parentModel.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = html;
			return false;
		}
	}
};