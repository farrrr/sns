//追加核心地區選擇物件 臨時放在這裡 
core.area ={
		//給工廠呼叫的介面
		_init:function(attrs){
			if(attrs.length == 3){
				core.area.init(attrs[1],attrs[2]);
			}else{
				return false;	//只是未了載入檔案
			}
		},
		init:function(xxx,bbb){
			
		}

		
};		