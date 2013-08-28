/**
 * 收藏模型Js核心插件
 * @author jason <yangjs17@yeah.net> 
 * @version TS3.0
 */
core.collection = {	
	// 初始化參數
	_init: function(attrs) {
		// 轉化為陣列
		attrs = $.makeArray(attrs);
		if(typeof attrs[6] == 'undefined') {
			attrs.push(0);
		}
		if(attrs.length == 7) {
			core.collection.init(attrs[1],attrs[2],attrs[3],attrs[4],attrs[5],attrs[6]);
		} else {
			return false;
		}
	},
	init: function(obj, type, sid, stable, sapp, isIco) {
		// 參數驗證
		if('undefined'==typeof(obj) || 'undefined'==typeof(sid) || 'undefined'==typeof(stable) || 'undefined'==typeof(sapp) ) {
			ui.error(L('PUBLIC_TIPES_ERROR'));
			return false;
		}
		// 添加收藏操作
		if($(obj).attr('rel') == 'add') {
			$.post(U('widget/Collection/addColl'), {sid:sid, stable:stable, sapp:sapp}, function(msg) {
				if(msg.status == 0) {
					ui.error(msg.data);
				} else {
					// 設定物件操作屬性
					$(obj).attr('rel', 'remove');
					
					if($('.count_' + stable + '_' + sid).length > 0) {
						if(isIco == 1) {
							$(obj).find('i').eq(0).addClass('current');
						} else {
							$(obj).html(L('PUBLIC_FAVORITED'));
						}
						var nums = $('.count_' + stable + '_' + sid).html();
						$('.count_' + stable + '_' + sid).html(parseInt(nums) + 1);
					} else {
						$(obj).html(L('PUBLIC_DEL_FAVORITE'));
					}
					updateUserData('favorite_count', 1);
					ui.success(L('PUBLIC_FAVORITE_SUCCESS'));
				}
			}, 'json');
			return false;
		}
		// 刪除收藏操作
		if($(obj).attr('rel') == 'remove') {
			$.post(U('widget/Collection/delColl'),{sid:sid,stable:stable},function(msg){
				if(msg.status == 1){	
					updateUserData('favorite_count',-1);
					if(type !='collection'){
						$(obj).attr('rel','add');
						if(isIco == 1) {
							$(obj).find('i').eq(0).removeClass('current');
						} else {
							$(obj).html(L('PUBLIC_FAVORITE'));
						}
						if($('.count_'+stable+'_'+sid).length >0 ){
							var nums = 	$('.count_'+stable+'_'+sid).html();
							$('.count_'+stable+'_'+sid).html(parseInt(nums)-1);
						}
					}else{
						$('#feed'+sid).fadeOut('slow');
					}
				}else{
					ui.error(msg.data);
				}
			},'json');
			return false;
		}
	}
};