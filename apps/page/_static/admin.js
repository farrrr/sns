var diy = {};

diy.pageCheck = function(form) {
    if(getLength(form.page_name.value) < 1) {
        ui.error('名稱不能為空');
        return false;
    }
    if(getLength(form.domain.value) < 1) {
        ui.error('域名不能為空');
        return false;
    }
    return true;
};
diy.addManager = function(id){
	var url = U('page/Admin/addManager')+'&id='+id;
	ui.box.load(url,'添加管理員');
};

diy.deletePage = function(id){
	if (id==null){
		id = admin.getChecked();
		if ( id == '' ){
			ui.error('請選擇刪除項');
			return false;
		}
	}
	$.post(U('page/Admin/doDeletePage'),{id:id},function (res){
		if(res>0){
			ui.success('刪除成功');
			setTimeout(function (){
				location.reload();
				},1000);
		}else{
			ui.error('不能刪除系統默認頁面')
		}
	});
};
diy.deleteCanvas = function(id){
	if (id==null){
		id = admin.getChecked();
		if ( id == '' ){
			ui.error('請選擇刪除項');
			return false;
		}
	}
	$.post(U('page/Admin/doDeleteCanvas'),{id:id},function (res){
		if(res==1){
			location.reload();
		}
	});
}
diy.canvasCheck = function(form){
	if(getLength(form.title.value) < 1) {
        ui.error('名稱不能為空');
        return false;
    }
    if(getLength(form.canvas_name.value) < 1) {
        ui.error('畫布名稱不能為空');
        return false;
    }
    if(getLength(form.data.value) < 1) {
        ui.error('畫布內容不能為空');
        return false;
    }
    return true;
}