/**
 * 後臺JS操作物件 -
 * 
 * 後臺所有JS操作都集中在此
 */

var admin = {};

/**
 * 收縮展開某個DOM
 */
admin.fold = function(id){
  	$('#'+id).slideToggle('fast');
};

/**
 * 處理ajax返回資料之後的重新整理操作
 */
admin.ajaxReload = function(obj,callback){
    if("undefined" == typeof(callback)){
        callback = "location.href = location.href";
    }else{
        callback = 'eval('+callback+')';
    }
    if(obj.status == 1){
        ui.success(obj.data);
        setTimeout(callback,1500);
     }else{
        ui.error(obj.data);
    }
};
/*
 * 上移物件
 */

admin.moveUp = function(obj,topList)
{
    var current=$(obj).parent().parent();
    var prev=current.prev();
    if(!topList || topList=='undefined'){
    	topList = 1;
    }
    if(current.index()>1)
    {
        current.insertBefore(prev);
        return true;
    }else{
    	ui.error(L('PUBLIC_NOMOVE_UP'));
        return false;
    }
}

/*
 * 下移物件
 */
admin.moveDown = function(obj)
{
    var current=$(obj).parent().parent();
    var next=current.next();
    if(next)
    {
        current.insertAfter(next);
        return true;
    }else{
    	ui.error(L('PUBLIC_NOMOVE_DOWN'));
        return false;
    }
};


admin.getChecked = function() {
    var ids = new Array();
    $.each($('#list input:checked'), function(i, n){
        if($(n).val() !='0' && $(n).val()!='' ){
            ids.push( $(n).val() );    
        }
    });
    return ids;
};

admin.checkon = function(o){
    if( o.checked == true ){
        $(o).parents('tr').addClass('bg_on');
    }else{
        $(o).parents('tr').removeClass('bg_on');
    }
};

admin.checkAll = function(o){
    if( o.checked == true ){
        $('#list input[name="checkbox"]').attr('checked','true');
        $('tr[overstyle="on"]').addClass("bg_on"); 
    }else{
        $('#list input[name="checkbox"]').removeAttr('checked');
        $('tr[overstyle="on"]').removeClass("bg_on");
    }
};
//繫結tr上的on屬性
admin.bindTrOn = function(){
    $("tr[overstyle='on']").hover(
      function () {
        $(this).addClass("bg_hover");
      },
      function () {
        $(this).removeClass("bg_hover");
      }
    );
};

/*日誌相關*/

//選擇某個日誌類型
admin.selectLog = function(value,def){
    if(!def){
        def ='';
    }
    if(value!='0'){
        $.post(U('admin/Home/_getLogGroup'),{app_name:value,def:def},function(msg){
            if($('#selectAfter').length > 0){
                $('#selectAfter').html(msg);
            }else{
                $('#form_app_name').after("<span id='selectAfter'>"+msg+"</span>");    
            }
            
        }) ;
    }else{
        $('#selectAfter').html('');
    }
};
//清理日誌
admin.cleanLogs = function(m){
    if(m!=6 && m!=12){
    	ui.error(L('PUBLIC_TIME_ISNOT'));       
    }else{
        $.post(U('admin/Home/_cleanLogs'),{m:m},function(msg){
            admin.ajaxReload(msg);
        },'json');
    }
};
//日誌歸檔
admin.logsArchive = function(){
    $.post(U('admin/Home/_logsArchive'),{},function(msg){
            admin.ajaxReload(msg);
        },'json') ;
};
//刪除一條日誌
admin.dellog = function(id,table){
	if(confirm(L('PUBLIC_DELETE_NOTE_TIPS'))){
		$.post(U('admin/Home/_delLogs'),{id:id,table:table},function(msg){
            admin.ajaxReload(msg,"$('#tr"+id+"').remove()");
		},'json');
	}
};
admin.delselectLog = function(table){
	var id = admin.getChecked();
	if(id==''){
		ui.error(L('PUBLIC_SELECT_DELETE_TIPS'));
        return false;
	}else{
		if(confirm(L('PUBLIC_DELETE_NOTE_SELECT_TIPS'))){	
			$.post(U('admin/Home/_delLogs'),{id:id,table:table},function(msg){
                admin.ajaxReload(msg);
			},'json');
		}
	}
};
/* 積分相關 */
//添加積分類型
admin.addCreditType = function(){
    location.href = U('admin/Config/addCreditType');
};
//驗證積分類型
admin.checkCreditType = function(form){
    if(form.CreditType.value =='' || getLength(form.CreditType.value) < 1){
        ui.error( L('PUBLIC_TYPENOT_ISNULL') );
        return false;
    }
    if(form.CreditName.value =='' || getLength(form.CreditName.value) < 1){
        ui.error( L('PUBLIC_TYPENAME_ISNULL') );
        return false;
    }
    return true;
};
admin.checkCreditSet = function(form){
    var uid_chose = $("input[name='uid_chose']:checked").val();
    if(uid_chose == '0'){
        if(form.uids.value==''){
            ui.error( '使用者ID不能為空' );
        }
    }
	//if(form.nums.value=='' || form.nums.value <1){
    if(form.nums.value===''){
		ui.error( L('PUBLIC_NUMBER_ISNULL') );
		return false;
	}
    var todo = $("input[name='todo']:checked").val();
    if(todo==1 && form.nums.value=='0'){
        ui.error( '數量不能為0' );
        return false;
    }
	return true;
};
admin.delCreditType = function(type){
   if(!type){
        var type = admin.getChecked(); 
   }
   if(type==''){
        ui.error( L('PUBLIC_PLEASE_SELECTDATA') );
   }else{
        $.post(U('admin/Config/_delCreditType'),{type:type},function(msg){
            admin.ajaxReload(msg);
        },'json');
   }
};
//部門子集管理
admin.selectDepart = function(pid,obj,sid){    
    obj.nextAll().remove();
    if("undefined"==typeof(sid))
    	sid ='';
    if( pid > 0 ){
        //obj.after('<span>載入中……</span>');    //TODO 修改成圖片
        $.post(U('admin/Public/selectDepartment'),{pid:pid,sid:sid},function(msg){
            obj.nextAll().remove();
            if(msg.status=="1"){
                obj.after(msg.data);
            }
        },'json');
    }
};

//默認生成部門 --已知道子部門集合
admin.departDefault = function(ids,domid){
	if("undefined" == typeof(ids) || ids==''){
		return false;
	}
	var id = ids.split(',');
	var obj = $('#'+domid);
	var objVal = obj.val();
	if(objVal =='' || objVal=='0'){
		return fasle;
	}
	var _defaultDepart = function(pid,_obj,i){
		var sid = id[i];
		$.post(U('admin/Public/selectDepartment'),{pid:pid,sid:sid},function(msg){
	        if(msg.status=="1"){
	            _obj.after(msg.data);
	            if("undefined" != id[i+1] && id[i+1] !='' &&  id[i+1] !='0'){
		        	var objVal = sid;
		        	var obj = $('#_parent_dept_'+pid);
		        	_defaultDepart(objVal,obj,i+1);
		        }
	        }
	    },'json');
	}
	_defaultDepart(objVal,obj,0);
};
// 添加使用者驗證資訊
admin.addUserSubmitCheck = function(form) {
    if(getLength(form.password.value) < 1) {
        ui.error(L('PUBLIC_PASSWORD_EMPTY'));
        return false;
    }
    
    if(!admin.checkUser(form)) {
        return false;
    }

    if($('input:[name="user_group[]"]:checked').length <1){
        ui.error('請選擇使用者組');
        return false;
    }
       
    return true;
};
// 檢驗使用者基本資訊
admin.checkUser = function(form){
    if(getLength(form.email.value) < 1){
        ui.error(L('PUBLIC_EMAIL_EMPTY'));
        return false;
    }
    if(getLength(form.uname.value) < 1){
        ui.error(L('PUBLIC_USER_EMPTY'));
        return false;
    }
/*    if(form.department_id.value < 1 && $('#department_show').html()==''){
        ui.error(L('PUBLIC_SELECT_DEPARTMENT'));
        return false;
    }*/
    if($(form).find('input[name="user_category[]"]').length != 0 && $(form).find('input[name="user_category[]"]:checked').length < 1) {
        ui.error("請選擇使用者職業資訊");
        return false;
    }
    
    return true;
};

admin.selectUserDepart = function(){
	var oldDepartMent = $('#form_department_id').val();
	var oldDepartMentName = $('#form_show_user_department').val(); 
	ui.box.load(U('widget/SelectDepartment/render')+'&departDomId=form_department_id&departId='+oldDepartMent+'+&departName='+oldDepartMentName+'&tpl=public&callback=admin.doSelectUserDepart','部門選擇');
};

//做完選擇
admin.doSelectUserDepart = function(){
	$('#form_department_id').val($('#tboxDepartMentId').val());
	$('#form_show_user_department').next().html($('#tboxDepartMentId').prev().html());
};
//驗證部門
admin.checkDepartment = function(form){
    if(form.title.value=='' || getLength(form.title.value)<1){
        ui.error( L('PUBLIC_DEPARENT_ISNULL') );
        return false;
    }
    return true;
};
admin.bindCatetree = function(){
    $("td[catetd='yes']").click(function(){
        var relId = $(this).attr('rel');
        $('#table'+relId).slideToggle();
    });
    $("span[rel='del']").click(function(){
    	var id = $(this).attr('cateid');
    	var callfunc = 'admin.'+$(this).attr('func')+'del('+id+')';
    	eval(callfunc);
    });
    $("span[rel='edit']").click(function(){
    	var id = $(this).attr('cateid');
    	var callfunc = 'admin.'+$(this).attr('func')+'edit('+id+')';
    	eval(callfunc);
    });
    $("span[rel='move']").click(function(){
    	var id = $(this).attr('cateid');
    	var callfunc = 'admin.'+$(this).attr('func')+'move('+id+')';
    	eval(callfunc);
    });
};
//分類相關的修改、刪除操作列表
//刪除部門
admin.departmentdel = function(id){
	var url = U('admin/Department/delDepartment')+'&id='+id;
	//ui.tbox("load('"+url+"','刪除部門')");
	ui.box.load(url,L('PUBLIC_DELETE_DEPARENT'));
};
admin.dodeldepart = function(id){

	var topid = $('#DepartmentSelect').attr('departmentid');
	 
	if("undefined" == typeof(topid) || topid == 0 ){
        ui.error( L('PUBLIC_SELECT_NEWDEPARENT') );
        return false;
    }
	$.post(U('admin/Department/dodelDepartment'),{id:id,topid:topid},function(msg){
		ui.box.close();
		admin.ajaxReload(msg);
	},'json');
};
//修改部門名稱
admin.departmentedit = function(id){
	var url = U('admin/Department/editDepartment')+'&id='+id;
	//ui.tbox("load('"+url+"','修改名稱')");
	ui.box.load(url,L('PUBLIC_EDIT_NAME'));
};
admin.doeditdepart = function(){
	var id = $('#editid').val();
	var title = $('#edittitle').val();	
	var display_order = $('#display_order').val();
    if(getLength(title) < 1){
          ui.error(L('PUBLIC_DEPARENT_ISNULL'));
          return false;
    }
	$.post(U('admin/Department/doeditDepartment'),{id:id,title:title,display_order:display_order},function(msg){
		ui.box.close();
		admin.ajaxReload(msg);
	},'json');
	
};
//移動部門
admin.departmentmove = function(id){
	var url = U('admin/Department/moveDepartment')+'&id='+id;
	//ui.tbox("load('"+url+"','移動名稱')");
	ui.box.load(url,L('PUBLIC_MOVE_NAME'));
};
admin.domovedepart = function(id,oldid){

	var topid = $('#DepartmentSelect').attr('departmentid');
	if(oldid == topid){
        ui.error(L('PUBLIC_EDIT_NO'));
        return false;
    }
	$.post(U('admin/Department/domoveDepartment'),{id:id,topid:topid},function(msg){
		ui.box.close();
		admin.ajaxReload(msg);
	},'json');	
};



admin.addUserGroup = function(){
     location.href = U('admin/UserGroup/addUsergroup');
};
//刪除使用者組
admin.delUserGroup = function(obj,gid){
    if("undefined" == typeof(gid) || gid ==''){
        gid = admin.getChecked();
        if(gid.length == 0){
            ui.error(L('PUBLIC_SELECT_EDIT_GROUP'),3);
            return false;
        }
    }
    if("string" == typeof(gid)){
    	if( gid <= 3){
    		ui.error( L('PUBLIC_ADMIN_GROUP_IS') );
    		return false;
    	}
    }else{
    	for(var i in gid){
    		if(gid[i] <=3 ){
    			ui.error( L('PUBLIC_ADMIN_GROUP_IS') );
    	    	return false;
    		}
    	}
    }
    if(confirm( L('PUBLIC_DELETE_GROUP_TIPES') )){
    	$.post(U('admin/UserGroup/delgroup'),{gid:gid},function(msg){
			admin.ajaxReload(msg);
    	},'json');
    }
};

admin.checkUserGroup = function(form){
    var user_group_name = form.user_group_name.value;
    if( getLength(user_group_name) < 1 ){
        form.user_group_name.value = '';
        ui.error( L('PUBLIC_PLEASE_SUERGROUPNAME') );
        return false;
    }
    return true;
};
//繫結許可權配置頁面checkbox時間
admin.bindperm = function(){
    $('.hAll').click(function(){
      var checked = $(this).attr("checked");
      if(!checked){
        var rel = $(this).attr("rel");
        var name = $(this).attr('name');
        $('.'+name).removeAttr("checked");
        $('.vAll').removeAttr("checked");
      }else{
        var rel = $(this).attr("rel");
        var name = $(this).attr('name');
        $('.'+name).attr("checked","checked");
      }
      
      });
    $('.vAll').click(function(){
      var checked = $(this).attr("checked");
      if(!checked){
        var rel = $(this).attr("rel");
        var name = $(this).attr('name');
        $('.'+name+"_"+rel).removeAttr("checked");
        $('.hAll').removeAttr("checked");
      }else{
        var rel = $(this).attr("rel");
        var name = $(this).attr('name');
        $('.'+name+"_"+rel).attr("checked","checked");
      }
      });
};
//刪除計劃任務
admin.delschedule = function(){
   var id = admin.getChecked();
   if(id==''){
	   ui.error( L('PUBLIC_SELECT_TASK_TIPES') );
       return false;
   }
   if(confirm( L('PUBLIC_DELETE_TASK') )){
	   $.post(U('admin/Home/doDeleteSchedule'),{id:id},function(msg){
			admin.ajaxReload(msg);
   	},'json');
   }
};
//內容管理用到的JS
admin.ContentEdit = function(_id,action,title,type){
	var id = ("undefined"== typeof(_id)|| _id=='') ? admin.getChecked() : _id;
    if(id==''){
        ui.error(L('PUBLIC_SELECT_TITLE_TYPE',{'title':title,'type':type}));
        return false;
	}
   if(confirm(L('PUBLIC_CONFIRM_DO',{'title':title,'type':type}))){
	   $.post(U('admin/Content/'+action),{id:id},function(msg){
			admin.ajaxReload(msg);
  	 },'json');
   }
};

admin.delArticle = function(_id,type){
	 var id = ("undefined"== typeof(_id)|| _id=='') ? admin.getChecked() : _id;
     var title = type==1 ?L('PUBLIC_ACCONTMENT'):L('PUBLIC_FOOTER_NOTE');
	 if(id==''){
    	ui.error( L('PUBLIC_PLEASE_DELETE_TITLE',{'title':title}) );
        return false;
	 }
    if(confirm( L('PUBLIC_ANSWER_DELETE_TITLE',{'title':title}) )){
	   $.post(U('admin/Config/delArticle'),{id:id,type:type},function(msg){
			admin.ajaxReload(msg);
   	 },'json');
    }
};

admin.delFeedback = function(_id){
   var id = ("undefined"== typeof(_id)|| _id=='') ? admin.getChecked() : _id;
   if(confirm( L('PUBLIC_ADD_NOTE_TIPES') )){
	   $.post(U('admin/Home/delFeedback'),{id:id},function(msg){
		 admin.ajaxReload('1',callback);
  	 });
   }
};

admin.delFeedbackType = function(_id){
	   //var id = ("undefined"== typeof(_id)|| _id=='') ? admin.getChecked() : _id;
	   if(confirm( L('PUBLIC_ANSWER_DELETE_CATEGORY') )){
		   $.post(U('admin/Home/delFeedbackType'),{id:id},function(msg){
			 admin.ajaxReload(msg,callback);
	  	 });
	   }
	};

admin.delsystemdata = function(id){
    if("undefined" == typeof(id) || id==''){
        ui.error( L('PUBLIC_PLEASE_DELTER_TIPES') );
     }
    if(confirm( L('PUBLIC_ANSWER_PLEASE_DELETE_TIPES') )){
       $.post(U('admin/Home/deladdsystemdata'),{key:id},function(msg){
            admin.ajaxReload(msg);
     },'json');
    }
};
//刪除導航配置
admin.delnav = function(id){
    if(confirm( L('PUBLIC_ANSWER_DELETE') )){
    	
        $.post(U('admin/Config/delNav'),{id:id},function(msg){
             admin.ajaxReload(msg);
      },'json');
     }
	
}
//驗證應用資訊
admin.checkAppInfo = function(form){
	if(form.app_name.value=='' || getLength(form.app_name.value) < 1 ){
		ui.error( L('PUBLIC_APPNAME_ISNULL') );
		return false;
	}
	if(form.app_alias.value=='' || getLength(form.app_alias.value) < 1){
		ui.error( L('PUBLIC_APPNAME_ISNULL') );
		return false;
	}
	if(form.app_entry.value=='' || getLength(form.app_alias.value) < 1){
		ui.error( L('PUBLIC_APPCENT_ISNULL') );
		return false;
	}
	 return true;
};
// 表單資訊驗證
admin.checkNavInfo = function(form) {
	if(form.navi_name.value.replace(/^ +| +$/g,'')==''){
		ui.error( L('PUBLIC_LEADNAME_ISNULL') );
		return false;
	}
	if(form.app_name.value.replace(/^ +| +$/g,'')==''){
		ui.error('英文名稱不能為空');
		return false;
	}
	if(form.url.value.replace(/^ +| +$/g,'')==''){
		ui.error( L('PUBLIC_HREF_ISNULL') );
		return false;
	}
	if(form.position.value.replace(/^ +| +$/g,'')==''){
		ui.error( L('PUBLIC_LEAD_ISNULL') );
		return false;
	}
	if(form.order_sort.value.replace(/^ +| +$/g,'')==''){
		ui.error( L('PUBLIC_APP_UPDATE_ISNULL') );
		return false;
	}
	return true;
};

admin.setAppStatus = function(app_id,status){
	if(app_id ==''){
	  var app_id = admin.getChecked();
	}
    if(app_id == ''){
        ui.error( L('PUBLIC_PLEASE_APP') );
        return false;
    }
	$.post(U('admin/Apps/setAppStatus'),{app_id:app_id,status:status},function(msg){
        admin.ajaxReload(msg);
    },'json');
};

admin.moveAppUp = function(obj,app_id){
	alert('up');
};

admin.moveAppDown = function(obj,app_id){
	alert('down');
};
//站點配置頁面JS
admin.siteConfigDefault = function(value){

	var html ='<input type="submit" value="'+L('PUBLIC_QUEDING')+'" onclick="return confirm(\''+L('PUBLIC_CLOSE_LOCALHOST_TIPES')+'\')"' 
		  +' id ="form_submit_2" class="btn_b">';
	$(html).insertAfter($('#form_submit')).hide();
	admin.siteConfig(value);
};
admin.siteConfig = function(value){
   
	$('.form2 dl').each(function(){
		var _id = $(this).attr('id');
		if(_id != "dl_site_closed"){
			if(value == "1"){
				if(_id != "dl_site_closed_reason"){
					$(this).show();
				}else{
					$(this).hide();
				}
			}else{
				if(_id == "dl_site_closed_reason"){
					$(this).show();
				}else{
					$(this).hide();
				}
			}
		}
	});
	if(value==1){
		$('#form_submit').show();$('#form_submit_2').hide();
	}else{
		$('#form_submit_2').show();$('#form_submit').hide();
	}
};

admin.registerConfig = function(value){
	$('.form2 dl').each(function(){
		var _id = $(this).attr('id');
		if( _id !='dl_register_type'){
			switch(value){
				case 'closed':
					if(_id != 'dl_register_close'){
						$(this).hide(); 
					}else{
						$(this).show();
					}	
					break;
				case 'open':
					if(_id == 'dl_email_suffix' || _id == 'dl_register_close'){
						$(this).hide();
					}else{
						$(this).show();
					}
					break;
				case 'appoint':
					if( _id == 'dl_register_close' ){
						$(this).hide();
					}else{
						$(this).show();
					}
					break;
			}
		}	
	});
};
admin.addmedal = function(value){
	if ( value == 0 ){
		$('#dl_attach_id').show();
		$('#dl_attach_small').show();
		$('#dl_medal_name').show();
		$('#dl_medal_desc').show();
	} else {
		$('#dl_attach_id').hide();
		$('#dl_attach_small').hide();
		$('#dl_medal_name').hide();
		$('#dl_medal_desc').hide();
	}
};
// 禁用使用者
admin.delUser = function(id){
    if("undefined" == typeof(id) || id=='')
        id = admin.getChecked();
    if(id==''){
        ui.error( L('PUBLIC_PLEASE_SELECT_NUMBER') );return false;
    }  
    if(confirm( L('PUBLIC_ANSWER_BUMBER_NO') )){
        $.post(U('admin/User/doDeleteUser'),{id:id},function(msg){
            admin.ajaxReload(msg);
        },'json');
   }
};
// 徹底刪除使用者
admin.trueDelUser = function(id){
    if("undefined" == typeof(id) || id=='')
        id = admin.getChecked();
    if(id==''){
        ui.error( L('PUBLIC_PLEASE_SELECT_NUMBER') );return false;
    }  
    if(confirm( L('PUBLIC_ANSWER_BUMBER_NO') )){
        $.post(U('admin/User/doTrueDeleteUser'),{id:id},function(msg){
            admin.ajaxReload(msg);
        },'json');
   }
};
// 恢複使用者
admin.rebackUser = function(id){
     if("undefined" == typeof(id) || id=='')
        id = admin.getChecked();
    if(id==''){
        ui.error( L('PUBLIC_PLEASE_SELECT_NUMBER') );return false;
    }  
    //alert(id);exit;
    if(confirm( L('PUBLIC_ANSWER_NUMBER') )){
        $.post(U('admin/User/doRebackUser'),{id:id},function(msg){
            admin.ajaxReload(msg);
        },'json');
   }
};
//啟用/取消啟用 使用者
admin.activeUser = function(id,type){
    if("undefined" == typeof(id) || id=='')
        id = admin.getChecked();
    if(id==''){
        ui.error( L('PUBLIC_PLEASE_SELECT_USER') );return false;
    }  
    $.post(U('admin/User/doActiveUser'),{id:id,type:type},function(msg){
            admin.ajaxReload(msg);
    },'json');
};

//啟用/取消啟用 使用者
admin.auditUser = function(id,type){
    if("undefined" == typeof(id) || id=='')
        id = admin.getChecked();
    if(id==''){
        ui.error( L('PUBLIC_PLEASE_SELECT_USER') );return false;
    }  
    $.post(U('admin/User/doAuditUser'),{id:id,type:type},function(msg){
            admin.ajaxReload(msg);
    },'json');
};

//轉移部門
admin.changeUserDepartment = function(){
	var id = admin.getChecked();
	if(id ==''){
		ui.error( L('PUBLIC_PLEASE_SELECT_USER') );return false;
	}
	var url = U('admin/User/moveDepartment')+'&uid='+id;
	//ui.tbox("load('"+url+"','轉移部門')");
	ui.box.load(url, L('PUBLIC_MOVE_DEPARTMENT') );
};



admin.domoveUserdepart = function(){
	var uid = $('#uid').val();
	var topid = $('#DepartmentSelect').attr('departmentid');
     
    if("undefined" == typeof(topid) || topid == 0 || topid == null){
        ui.error( L('PUBLIC_SELECT_NEWDEPARENT') );
        return false;
    }
	$.post(U('admin/User/domoveDepart'),{uid:uid,topid:topid},function(msg){
		admin.ajaxReload(msg);
	},'json');
};

admin.domoveUsergroup = function(){
	var ids = new Array();
    $.each($('#movegroup input:checked'), function(i, n){
        if($(n).val() !='0' && $(n).val()!='' ){
            ids.push( $(n).val() );    
        }
    });
    if(ids.length<1){
    	ui.error( L('PUBLIC_PLEASE_SELECT_USERGROUP') );return false;
    }	
    ids = ids.join(',');
    var uid = $('#uid').val();
    $.post(U('admin/User/domoveUsergroup'),{uid:uid,user_group_id:ids},function(msg){
    	admin.ajaxReload(msg);
    },'json');
};

//轉移使用者組
admin.changeUserGroup = function(){
	var id = admin.getChecked();
	if(id ==''){
		ui.error( L('PUBLIC_PLEASE_SELECT_USER') );return false;
	}
	var url = U('admin/User/moveGroup')+'&uid='+id;
	//ui.tbox("load('"+url+"','轉移使用者組')");
	ui.box.load(url,L('PUBLIC_MOVE_USERGROUP'));
};

//添加使用者

//刪除資料欄位
admin.delProfileField = function(id,t){
   if("undefined" == typeof(id)){
	   var id = admin.getChecked();
   }
   if(t==1){
    var msg  = L('PUBLIC_PLEASE_DELETE_FIELD');
    var conf = L('PUBLIC_ANSWER_DELETE_FIELD');
   }else{
    var msg  = L('PUBLIC_SELECT_DELETE_CATEGORY');
    var conf = L('PUBLIC_ANSER_DELETE_CATEGORY');
   }
   if(id==''){
       ui.error(msg);
       return false;
   }
   if(confirm(conf)){
       $.post(U('admin/User/doDeleteProfileField'),{id:id},function(msg){
            admin.ajaxReload(msg);
    },'json');
   }
};


admin.upload = function(type,obj){
    if("undefined"  != typeof(core.uploadFile)){
        core.uploadFile.filehash = new Array();
    }
	core.plugInit('uploadFile',obj,function(data){
        $('.input-content').remove();
        $('#show_'+type).html('<img class="pic-size" src="'+data.src+'">');
        $('#form_'+type).val(data.attach_id);    
    },'image');
};


admin.setCredit = function(v){
	if("undefined"==typeof(v)){
		v = 0;
	}
	if(v==0){
		$('#dl_uids').show();
		$('#dl_userGroup').hide();
	}else{
		$('#dl_uids').hide();
		$('#dl_userGroup').show();
	}
};

admin.delCreditNode = function(id){
    if("undefined" == typeof(id)){
       var id = admin.getChecked();
   }
   if(id==''){
       ui.error( L('PUBLIC_PLEASE_SELECT_INTEG0RL') );
       return false;
   }
   if(confirm( L('PUBLIC_ANSWER_INTEG0RL') )){
       $.post(U('admin/Apps/delCreditNode'),{id:id},function(msg){
            admin.ajaxReload(msg);
    },'json');
   }
};

admin.delPermNode = function(id){
 if("undefined" == typeof(id)){
       var id = admin.getChecked();
   }
   if(id==''){
       ui.error( L('PUBLIC_PLEASE_DELETEOPINT') );
       return false;
   }
   if(confirm( L('PUBLIC_ANSWER_SELECT_OPINT') )){
       $.post(U('admin/Apps/delPermNode'),{id:id},function(msg){
            admin.ajaxReload(msg);
    },'json');
   }
};

admin.checkCreditNode = function(obj){
    var reg = /\W+/g;
    var appname = $('#form_appname').val();
    var action = $('#form_action').val();
    if(reg.test(appname)){
        ui.error( L('PUBLIC_ADMIN_APP_TIPES') ); return false;
    }else{
        if(reg.test(action)){
            ui.error( L('PUBLIC_GONGFU_ISNULL') );return false;
        }
    }
    return true;
};

admin.checkFeedNode = function(obj){
    if(obj.appname.value == '' || obj.nodetype.value==''){
        ui.error( L('PUBLIC_APP_WEIBO_ISNULL') );
        return false;
    }
    return true;
};
admin.checkPermNode = function(obj){
    var reg = /\W+/g;
    var appname = $('#form_appname').val();
    var rule = $('#form_rule').val();
    if(reg.test(appname)){
        ui.error( L('PUBLIC_ADMIN_APP_TIPES') ); return false;
    }else{
        if(reg.test(rule)){
            ui.error( L('PUBLIC_ADMIN_OPINT_TIPES') );return false;
        }
    }
    return true;
};

admin.testEmail = function(){
    var email_sendtype = $('#form_email_sendtype').val();
    var email_host = $('#form_email_host').val();
    var email_port = $('#form_email_port').val();
    var email_ssl = $('input:radio[name="email_ssl"]:checked').val();
    var email_account = $('#form_email_account').val();
    var email_password = $('#form_email_password').val();
    var email_sender_name = $('#form_email_sender_name').val();
    var email_sender_email = $('#form_email_sender_email').val();
    var sendto_email = $('#form_email_test').val();
    if ( sendto_email == ''){
    	ui.error('測試郵件地址未填');
    	return;
    }
    var eMailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/;
        if(!eMailReg.test(sendto_email)) {
        ui.error("郵箱格式不正確");
        return false;
    }
    $.post(U('admin/Public/test_email'),
         {email_sendtype:email_sendtype,email_host:email_host,email_port:email_port,email_ssl:email_ssl,email_account:email_account,
          email_password:email_password,email_sender_name:email_sender_name,sendto_email:sendto_email,email_sender_email:email_sender_email},
        function(msg){
        if(msg == 1 ){
            ui.success( L('PUBLIC_TEST_MAIL_SUCCESS') );    
        }else{
            alert(msg);
        }
    });
};


admin.checkProfile = function(form){
    if(form.field_key.value=='' || getLength(form.field_key.value) < 1){
        ui.error( L('PUBLIC_KEY_ISNULL') );return false;
    }
    if(form.field_name.value==''　|| getLength(form.field_name.value) < 1 ){
        ui.error( L('PUBLIC_NAME_ISNULL') );return false;
    }
    if(form.field_type.value==''　|| getLength(form.field_type.value) < 1 ){
        ui.error( '欄位類型不能為空' );return false;
    }
    return true;
};

/*** 添加雙語內容 ***/
// 跳轉到添加語言頁面
admin.updateLangContent = function(sid) {
    location.href = U('admin/Config/updateLangContent') + '&sid=' + sid;
};

// 刪除語言配置內容
admin.deleteLangContent = function(id) {
    id = (id != '') ? id : admin.getChecked().join(',');
    if('undefined' === typeof(id) || id == '') {
        ui.error( L('PUBLIC_PLEASE_DELTER_TIPES') );
        return false;
    }

    if(confirm( L('PUBLIC_ANSWER_PLEASE_DELETE_TIPES') )) {
        $.post(U('admin/Config/deleteLangContent'), {lang_id:id}, function(msg) {
            admin.ajaxReload(msg);
        }, 'json');
    }
};

//更新wigdet
admin.updateWidget = function(){
    $.post(U('widget/Diy/updateWidget'),{},function(data){
        ui.success(data);
    });
};

admin.configWidget = function(id){
    ui.box.load(U('widget/Diy/config')+'&id='+id,L('PUBLIC_SET_WIDGET'));
};

admin.checkAddArticle = function(form){
    if(form.title.value == '' ||　getLength(form.title.value) < 1 ){
        ui.error( L('PUBLIC_TITLE_ISNULL') );return false;
    }

    if(!admin.checkEditor(Editor_content,form.content)){
        return false;
    }
    return true;
};

admin.checkMessage = function(form){

    if(!admin.checkEditor(Editor_content,form.content)){
        return false;
    }
    return true;
};

admin.checkEditor = function(editor,content){
    
    var html = editor.html();
    content.value =  html;

    var t =$('<div></div>');
    t.html(html);
    
    var imgnums = t.find('img').size();

    html = html.replace(/&nbsp;/g,"").replace(/\s+/g,"").replace(/<.*?>/g,"");

    if(getLength(html)<1 && imgnums <1 ){

        ui.error( L('PUBLIC_INNULL') );
        return false;
    }
    
    return true;
};

admin.delTag = function(obj,tag_id,table,row_id){
    if(confirm(L('PUBLIC_DELETE_TAG_CONFIRM'))){
        $.post(U('admin/Home/deltag'),{tag_id:tag_id,table:table,row_id:row_id},function(msg){
            if(msg.status == 1){
                 ui.success(msg.data);
                $(obj).parent().parent().remove();    
            }else{
                ui.error(msg.data);
            }
            
        },'json');
    }
};

admin.appnav = function(obj,name,url){
    if($(obj).attr('add') == 0){
        window.parent.addTonav(name,url);
        $(obj).html(L('PUBLIC_REMOVE_NAV'));
        $(obj).attr('add',1);
    }else{
        var appname = url.split('/');
        window.parent.removeFromNav(appname[0]);
        $(obj).html(L('PUBLIC_ADD_NAV'));
        $(obj).attr('add',0);
    } 
};


admin.sendEmailList = function(){
    $('#email_msg').html('發送中……');
    $.post(U('admin/Config/dosendEmail'),{},function(msg){
        $('#email_msg').html(msg);
    });
};

//後臺取消小名片
M.addEventFns({
    face_card:{
        load:function(){
            //載入小名片js
            return true;
        },
        mouseenter:function(){
            return true;
        },
        mouseleave:function(){
            return true;
        },
        blur:function(){
            return true
        }
    }
});

/**
 * 認證通過、駁回
 * @param  integer id  認證ID
 * @param  integer status 認證狀態
 * @param  string info 認證資料
 * @return void
 */
admin.verify = function(id,status,isgroup,info){
  //  alert(info);exit;
    if("undefined" == typeof(id) || id=='')
        id = admin.getChecked();
    if(id == ''){
        if(status == 1){
            if(isgroup == 6){
                ui.error('請選擇要通過認證的企業');
                return false;
            }else{
                ui.error('請選擇要通過認證的使用者');
                return false;
            }
        }else{
            if(isgroup == 6){
                ui.error('請選擇要駁回認證的企業');
                return false;
            }else{
                ui.error('請選擇要駁回認證的使用者');
                return false;
            }
        }
    }
    if(status == 1){
        ui.box.load(U('admin/User/editVerifyInfo')+'&id='+id+'&status='+status+'&info='+encodeURI(info),'編輯認證資料');
    }else{
        $.post(U('admin/User/doVerify'),{id:id,status:status},function(msg){
            admin.ajaxReload(msg);
        },'json');
    }
};

/**
 * 添加認證提交驗證
 * @param {[type]} form [description]
 * @return bool
 */
admin.addVerifySubmitCheck = function(form){
    if(!admin.checkAddVerify(form)){
        return false;
    } 
    return true;
};

/**
 * 添加認證驗證表單
 * @param  {[type]} form [description]
 * @return bool
 */
admin.checkAddVerify = function(form){
    var Regx1 = /^[0-9]*$/;
    var Regx2 = /^[A-Za-z0-9]*$/;
    var Regx3 = /^[\u4E00-\u9FA5]+$/;

    if(getLength(form.uname.value) < 1){
        ui.error('請選擇使用者');
        return false;
    }
    if($(":radio:checked").val() == 6){
        if(getLength(form.company.value) < 1){
            ui.error('請輸入企業名稱');
            return false;
        }
        if(getLength(form.realname.value) < 1){
            ui.error(L('請輸入法人姓名'));
            return false;
        }
        if(getLength(form.idcard.value) < 1){
            ui.error(L('請輸入營業證號'));
            return false;
        }
        if(getLength(form.phone.value) < 1){
            ui.error(L('請輸入聯繫方式'));
            return false;
        }
        if(getLength(form.info.value) < 1){
            ui.error(L('請輸入認證資料'));
            return false;
        }
        if(!Regx3.test($.trim(form.realname.value)) || getLength($.trim(form.realname.value))>10){
            ui.error('請輸入正確的法人姓名');
            return false;
        }   
        if(!Regx2.test(form.idcard.value)){
            ui.error('請輸入正確的營業證號格式');
            return false;
        }
        // if(getLength(form.info.value) > 70){
        //     ui.error(L('認證資料不能超過140個字元'));
        //     return false;
        // }
    }else{     
        if(getLength(form.realname.value) < 1){
            ui.error(L('請輸入真實姓名'));
            return false;
        }
        if(getLength(form.idcard.value) < 1){
            ui.error(L('請輸入身份證號'));
            return false;
        }
        if(getLength(form.phone.value) < 1){
            ui.error(L('請輸入手機號碼'));
            return false;
        }
        if(getLength(form.info.value) < 1){
            ui.error(L('請輸入認證資料'));
            return false;
        }
        if(!Regx3.test($.trim(form.realname.value)) || getLength($.trim(form.realname.value))>10){
            ui.error('請輸入正確的真實姓名');
            return false;
        }   
        if($.trim(form.idcard.value).length !== 18 || !Regx1.test($.trim(form.idcard.value).substr(0,17)) || !Regx2.test($.trim(form.idcard.value).substr(-1,1))){
            ui.error('請輸入正確的身份證號碼格式');
            return false;
        }
        if($.trim(form.phone.value).length !== 11 || !Regx1.test($.trim(form.phone.value))){
            ui.error('請輸入正確的手機號碼格式');
            return false;
        }
        // if(getLength(form.info.value) > 70){
        //     ui.error(L('認證資料不能超過140個字元'));
        //     return false;
        // }
    }  
    return true;
};

/**
 * 後臺添加認證單選按鈕切換
 * @param  integer value 認證類型
 * @return void
 */
admin.addVerifyConfig = function(value){
    if(value == 6){
        $('#dl_company').show();
        $('#dl_realname dt').html("<font color='red'> * </font>法人姓名：");
        $('#dl_idcard dt').html("<font color='red'> * </font>營業執照號：");
        $('#dl_phone dt').html("<font color='red'> * </font>聯繫方式：");
    }else{
        $('#dl_company').hide();
        $('#dl_realname dt').html("<font color='red'> * </font>真實姓名：");
        $('#dl_idcard dt').html("<font color='red'> * </font>身份證號碼：");
        $('#dl_phone dt').html("<font color='red'> * </font>手機號碼：");
    }
    $.post(U('admin/User/getVerifyCategory'),{value:value},function(data){
        if(data){
            $('#dl_user_verified_category_id').css('display','block');
            $('#form_user_verified_category_id').html(data);
        }else{
            $('#dl_user_verified_category_id').css('display','none');
        }
    });
};

/**
 * 後臺添加認證單選按鈕切換
 * @param  integer value 認證類型
 * @return void
 */
admin.addVerifyConfig = function(value){
    if(value == 6){
        $('#dl_company').show();
        $('#dl_realname dt').html("<font color='red'> * </font>法人姓名：");
        $('#dl_idcard dt').html("<font color='red'> * </font>營業執照號：");
        $('#dl_phone dt').html("<font color='red'> * </font>聯繫方式：");
    }else{
        $('#dl_company').hide();
        $('#dl_realname dt').html("<font color='red'> * </font>真實姓名：");
        $('#dl_idcard dt').html("<font color='red'> * </font>身份證號碼：");
        $('#dl_phone dt').html("<font color='red'> * </font>手機號碼：");
    }
    $.post(U('admin/User/getVerifyCategory'),{value:value},function(data){
        if(data){
            $('#dl_user_verified_category_id').css('display','block');
            $('#form_user_verified_category_id').html(data);
        }else{
            $('#dl_user_verified_category_id').css('display','none');
        }
    });
};

admin.editVerifyConfig = function(value){
    $.post(U('admin/User/getVerifyCategory'),{value:value},function(data){
        if(data){
            $('#dl_user_verified_category_id').css('display','block');
        }else{
            $('#dl_user_verified_category_id').css('display','none');
        }
    });
}

/**
 * 後臺編輯認證資訊提交驗證
 * @param  {[type]} form [description]
 * @return bool
 */
admin.editVerifySubmitCheck = function(form){
    if(!admin.checkEditVerify(form)){
        return false;
    } 
    return true;
};

/**
 * 編輯認證驗證表單
 * @param  {[type]} form [description]
 * @return bool
 */
admin.checkEditVerify = function(form){
    var Regx1 = /^[0-9]*$/;
    var Regx2 = /^[A-Za-z0-9]*$/;
    var Regx3 = /^[\u4E00-\u9FA5]+$/;
    
    if($(":radio:checked").val() == 6){
        if(getLength(form.company.value) < 1){
            ui.error('請輸入企業名稱');
            return false;
        }
        if(getLength(form.realname.value) < 1){
            ui.error(L('請輸入法人姓名'));
            return false;
        }
        if(getLength(form.idcard.value) < 1){
            ui.error(L('請輸入營業執照號'));
            return false;
        }
        if(getLength(form.phone.value) < 1){
            ui.error(L('請輸入聯繫方式'));
            return false;
        }
        if(getLength(form.reason.value) < 1){
            ui.error(L('請輸入認證理由'));
            return false;
        }
        if(getLength(form.info.value) < 1){
            ui.error(L('請輸入認證資料'));
            return false;
        }
        if(!Regx3.test($.trim(form.realname.value)) || getLength($.trim(form.realname.value))>10){
            ui.error('請輸入正確的法人姓名');
            return false;
        }   
        if(!Regx2.test(form.idcard.value)){
            ui.error('請輸入正確的營業證號格式');
            return false;
        }
        // if(getLength(form.info.value) > 70){
        //     ui.error(L('認證資料不能超過140個字元'));
        //     return false;
        // }
    }else{     
        if(getLength(form.realname.value) < 1){
            ui.error(L('請輸入真實姓名'));
            return false;
        }
        if(getLength(form.idcard.value) < 1){
            ui.error(L('請輸入身份證號'));
            return false;
        }
        if(getLength(form.phone.value) < 1){
            ui.error(L('請輸入手機號碼'));
            return false;
        }
        if(getLength(form.reason.value) < 1){
            ui.error(L('請輸入認證理由'));
            return false;
        }
        if(getLength(form.info.value) < 1){
            ui.error(L('請輸入認證資料'));
            return false;
        }
        if(!Regx3.test($.trim(form.realname.value)) || getLength($.trim(form.realname.value))>10){
            ui.error('請輸入正確的真實姓名');
            return false;
        }   
        if($.trim(form.idcard.value).length !== 18 || !Regx1.test($.trim(form.idcard.value).substr(0,17)) || !Regx2.test($.trim(form.idcard.value).substr(-1,1))){
            ui.error('請輸入正確的身份證號碼格式');
            return false;
        }
        if($.trim(form.phone.value).length !== 11 || !Regx1.test($.trim(form.phone.value))){
            ui.error('請輸入正確的手機號碼格式');
            return false;
        }
        // if(getLength(form.info.value) > 70){
        //     ui.error(L('認證資料不能超過140個字元'));
        //     return false;
        // }
    }  
    return true;
};
// 驗證CheckBox選中的個數
admin.checkBoxNums = function(obj, nums) {
    var name = $(obj).attr('name');
    var len = $('#dl_' + name.replace(/\[\]/, '')).find('input:checked').length;
    len > nums && $(obj).attr('checked', false);
    return false;
};

/**
 * 設定話題類型
 * @param integer type 要設定的話題類型 1:推薦  2:精華   3:鎖定
 * @param integer topic_id  話題ID 
 * @param integer value 話題現有的類型值，改為相反的。0變為1，1變為0
 */
admin.setTopic = function(type,topic_id,value){
    if(!topic_id){
       var topic_id = admin.getChecked();
    }
    if(topic_id==''){
        ui.error('請選擇話題');return false;
    }
    $.post(U('admin/Content/setTopic'),{topic_id:topic_id,type:type,value:value},function(msg){
            admin.ajaxReload(msg);
    },'json');
};

/**
 * 添加/編輯話題驗證
 * 
 */
admin.topicCheck = function(form){
    if(getLength(form.topic_name.value) < 1){
        ui.error('請輸入話題名稱');
        return false;
    }
    if(getLength(form.note.value) < 1){
        ui.error('請輸入話題註釋');
        return false;
    }
    if(getLength(form.domain.value) > 0){
        var Regx2 = /^[A-Za-z0-9]*$/;
        if(!Regx2.test(form.domain.value)){
            ui.error('請輸入正確的話題域名格式');
            return false;
        }
    }
    return true;
};

/**
 * 移除官方使用者
 * @param integer id 官方使用者列表主鍵ID
 * @return void
 */
admin.removeOfficialUser = function(id)
{
    // 獲取使用者ID
    if(typeof id === "undefined") {
        id = admin.getChecked();
        id = id.join(',');
    }
    // 提交操作
    $.post(U('admin/User/doRemoveOfficialUser'), {id:id}, function(msg) {
        if(msg.status == 1) {
            ui.success(msg.data);
            location.href = location.href;
            return false;
        } else {
            ui.error(msg.data);
            return false;
        }
    }, 'json');
    return false;
};
//刪除任務
admin.delcustomtask = function (id){
    // 獲取任務ID
    if(typeof id === "undefined") {
        id = admin.getChecked();
        id = id.join(',');
    }
    if ( id == '' ){
    	ui.error('請選擇刪除項');
    	return;
    }
    if ( confirm('刪除無法恢復請確認是否刪除！') ){
	    // 提交操作
	    $.post(U('admin/Task/doDeleteCustomTask'), {id:id}, function(msg) {
	        if(msg.status == 1) {
	            ui.success(msg.data);
	            location.href = location.href;
	        } else {
	            ui.error(msg.data);
	        }
	    }, 'json');
    }
}
//刪除勳章
admin.deletemedal = function (id){
    // 獲取勳章ID
    if(typeof id === "undefined") {
        id = admin.getChecked();
        id = id.join(',');
    }
    if ( id == '' ){
    	ui.error('請選擇刪除項');
    	return;
    }
    if ( confirm('刪除無法恢復請確認是否刪除！') ){
	    // 提交操作
	    $.post(U('admin/Medal/doDeleteMedal'), {id:id}, function(msg) {
	        if(msg.status == 1) {
	            ui.success(msg.data);
	            location.href = location.href+"&tabHash=customIndex";
	        } else {
	            ui.error(msg.data);
	        }
	    }, 'json');
    }
}
//刪除使用者勳章
admin.deleteusermedal = function (id){
    // 獲取使用者勳章ID
    if(typeof id === "undefined") {
        id = admin.getChecked();
        id = id.join(',');
    }
    if ( id == '' ){
    	ui.error('請選擇刪除項');
    	return;
    }
    if ( confirm('刪除無法恢復請確認是否刪除！') ){
    	 // 提交操作
        $.post(U('admin/Medal/doDeleteUserMedal'), {id:id}, function(msg) {
            if(msg.status == 1) {
                ui.success(msg.data);
                location.href = location.href;
            } else {
                ui.error(msg.data);
            }
        }, 'json');
    }
}

/**
 * 添加認證分類
 */
admin.addVerifyCategory = function(){
    ui.box.load(U('admin/User/addVerifyCategory'), "添加認證分類");
}

/**
 * 添加認證分類
 */
admin.editVerifyCategory = function(user_verified_category_id){
    ui.box.load(U('admin/User/editVerifyCategory')+'&user_verified_category_id='+user_verified_category_id, "編輯認證分類");
}

/**
 * 刪除認證分類
 */
admin.delVerifyCategory = function(user_verified_category_id){
    if(confirm('確定刪除此分類嗎？')){
        $.post(U('admin/User/delVerifyCategory'), {user_verified_category_id:user_verified_category_id}, function(msg){
              admin.ajaxReload(msg);
        },'json');
    }
}

/**
 * 註冊配置驗證
 */
admin.checkRegisterConfig = function(){
    var tag_num = $('#form_tag_num').val();
    if(getLength(tag_num) < 1){
        ui.error('請輸入允許設定標籤數量');
        return false;
    }
    if(isNaN(tag_num)){
        ui.error('允許設定標籤數量必須為數字');
        return false;
    }
    if(tag_num < 0){
        ui.error('允許設定標籤數量不能小於0');
        return false;
    }
    //alert($('#dl_default_user_group input:checked').val());
    if(!$('#dl_default_user_group input:checked').val()){
        ui.error('請選擇默認使用者組');
        return false;
    }
    return true;

}

/**
 * 刪除模板操作
 * @param integer id 模板ID
 * @return void
 */
admin.delTemplate = function(id)
{
    // 獲取模板ID
    if(typeof id === 'undefined') {
        id = admin.getChecked();
        id = id.join(',');
    }
    // 非同步提交，刪除操作
    if(confirm('是否刪除該模板？')) {
        $.post(U('admin/Content/doDelTemplate'), {id:id}, function(res) {
            if(res.status == 1) {
                ui.success(res.data);
                location.href = location.href;
            } else {
                ui.error(res.data);
            }
        }, 'json');
    }
    return false;
};