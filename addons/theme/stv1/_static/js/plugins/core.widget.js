//追加核心widget選擇 臨時放在這裡 
core.widget = {
		//給工廠呼叫的介面
		_init:function(attrs){
			return false;	//只是未了載入檔案
		},
		//移除
		removeWidget:function(obj,args,pobj){
			var doremove = function(){
				//移出操作
				var url = U('widget/Diy/del')+'&diyId='+args.diyId+'&appname='+args.appname+'&widget_name='+args.widget_name;
				$.get(U('widget/Diy/del'),{diyId:args.diyId,appname:args.appname,widget_name:args.widget_name},function(data){
					if(data.status == 0){
						ui.error( L('PUBLIC_DELETE_ERROR') );
					}else{
						ui.success( L('PUBLIC_DELETE_SUCCESS') );
						$(pobj).remove();
					}			
				},'json');
			};
			ui.confirm(obj,L('PUBLIC_MOVE_WEIGET'),doremove);
		},
		addWidget:function(args){
			var url = U('widget/Diy/addWidget')+'&widget_user_id='+args.widget_user_id+'&diyId='+args.diyId;
			ui.box.load(url,L('PUBLIC_ADD_WEIGET'));
		},
		afterSet:function(data){
			if(data.status == 0){
				ui.error(data.info);
			}else{
				ui.success(data.info);
				//todo 以後優化成局部重新整理
				setTimeout("location.href = location.href",1000);	
			}
		},
		//添加操作
		doadd:function(diyId,selected){
			$.post(U('widget/Diy/doadd'),{diyId:diyId,selected:selected},function(data){
				if(data.status == 0){
					ui.error(data.info);
				}else{
					ui.success(data.info);
					//todo 以後優化成局部重新整理
					setTimeout("location.href = location.href",1000);	
				}
			},'json');
		},
		doconfig:function(diyId,selected){
			$.post(U('widget/Diy/doconfig'),{diyId:diyId,selected:selected},function(data){
				if(data.status == 0){
					ui.error(data.info);
				}else{
					ui.success(data.info);
					setTimeout("location.href = location.href",1000);	
				}
			},'json');
		},
		dosort:function(args,obj){
			var id = args.diyId;
			M(obj);
			var child = obj.childModels['widget_box']; 	//重新獲取子節點
			var targets = new Array();
			for(var i in child){
				var a = M.getModelArgs(child[i]);
				targets[i] = a.appname+':'+a.widget_name;
			}
			targets = targets.join(',');
			$.post(U('widget/Diy/dosort'),{diyId:id,targets:targets},function(){});
		}
};		