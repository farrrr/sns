core.dropnotify = {
	_init:function(attrs){
			if(attrs.length == 1){
				return false; // 意思是執行插件 只是為了載入此檔案
			} 
			this.init(attrs[1],attrs[2]);
	},
	init:function(dropclass,parentObjId){

		this.dropclass = dropclass;
		this.parentObjId = parentObjId;

		this.close = false;
		var _this = this;

		this.count();
		return false;
	},
	//顯示父物件
	dispayParentObj:function(){
		if(this.close == false){
			 $('#'+this.parentObjId).show();
			 $('.'+this.dropclass).show();
		}
	},
	//隱藏
	hideParentObj:function(){
		if("undefined" != typeof(this.parentObjId)){
			$('#'+this.parentObjId).hide();
		}else{
			if($('#'+this.parentObjId).length > 0){
				$('#'+this.parentObjId).hide();	
			}
		}
	},
	//關閉 不在迴圈顯示
	closeParentObj:function(){
		this.close = true;
		this.hideParentObj();
	},
	count:function(){
		var _this = this;

		var noticeTipsText = {
                unread_notify:  L('PUBLIC_SYSTEM_MAIL'),
                unread_atme:    L('PUBLIC_SYSTEM_TAME'),
                unread_comment: L('PUBLIC_SYSTEM_CONCENT'),
                unread_message: L('PUBLIC_SYSTEM_PRIVATE_MAIL'),
                new_folower_count: L('PUBLIC_SYSTEM_FOLLOWING'),
                unread_group_atme: '條群聊@提到我',
                unread_group_comment: '條群組評論'
        };
        var loopCount = '';
		var getCount = function() {
 			
			$.get( U( "widget/UserCount/getUnreadCount" ), function( msg ) {	
				if("undefined" == typeof(msg.data) || msg.status != 1){
					return false;
				}else{
					
					var txt =msg.data;
					if(txt.unread_total <= 0){
						_this.hideParentObj();
						return false;
					}else{
						_this.dispayParentObj();
					}
					$('.'+_this.dropclass).each(function(){
						$(this).find('li').each(function(){
							var name = $(this).attr('rel');
							num  =  txt[name] ;
							if(num > 0){
								$(this).find('span').html(num +noticeTipsText[name]);
								$(this).show();
							}else{
								$(this).hide();
							}
						});
					});
				}
			},'json');
		};
		loopCount = setInterval( getCount, 60000 );
		
		getCount();

       
	}
};