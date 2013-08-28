
/**
 * 舉報元件JS
 * @author jason
 * @version TS3.0
 */
core.denouce = {
		//給工廠呼叫的介面
		_init: function(attrs)
		{
			if(attrs.length == 4) {
				core.denouce.init(attrs[1],attrs[2],attrs[3]);
			} else {
				return false;
			}
		},
		//aid 資源ID，資源類型 目前都是feed,fuid被舉報的使用者ID
		init: function(aid, type, fuid)
		{
			$.post(U('widget/Denouce/isDenounce'), {aid:aid, type:type}, function(msg) {
				if(msg.status == 1) {
					ui.error(msg.data);
				} else {
					ui.box.load(U('widget/Denouce/index')+'&aid='+aid+'&type='+type+'&fuid='+fuid, L('PUBLIC_REPORT'));
				}
			}, 'json');
		},
		// 提交舉報
		post:function()
		{
			var uid = $('#denouce_uid').val();
			var aid = $('#denouce_aid').val();
			var content = $('#denouce_content').html();
			var from = $('#denouce_from').val();
			var fuid = $('#denouce_fuid').val();
			var reason = $('#denouce_reason').val();
			var source_url = $('#denouce_source_url').val();
			$.post(U('widget/Denouce/post'), {uid:uid, aid:aid, content:content, from:from, fuid:fuid, reason:reason, source_url:source_url}, function(msg) {
				if(msg.status == 0) {
					ui.error(msg.data);
				} else {
					ui.success(msg.data);
				}
				ui.box.close();
			},'json');
		}
	};