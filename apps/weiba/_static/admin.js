/**
 * 微吧後臺JS操作物件 -
 * 
 * 微吧後臺所有JS操作都集中在此
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

admin.upload = function(type,obj){
    if("undefined"  != typeof(core.uploadFile)){
        core.uploadFile.filehash = new Array();
    }
    core.plugInit('uploadFile',obj,function(data){
        $('.input-content').remove();
        $('#show_'+type).html('<img src="'+data.src+'" width="100" height="100">');
        $('#form_'+type).val(data.attach_id);    
    },'image');
};

admin.checkAddWeiba = function(form){
	if(getLength(form.weiba_name.value) < 1){
		ui.error('請輸入微吧名稱');
		return false;
	}
	if(getLength(form.logo.value) < 1){
		ui.error('請上傳logo');
		return false;
	}
	if(getLength($('#form_intro').val()) < 1){
		ui.error('請輸入微吧簡介');
		return false;
	}
    return true;	
};

/**
 * 設定微吧推薦狀態
 * @param integer weiba_id 微吧ID
 * @param integer type 當前微吧的推薦狀態
 * @return void
 */
admin.recommend = function(weiba_id, type){
    $.post(U('weiba/Admin/setRecommend'),{weiba_id:weiba_id,type:type},function(msg){
        admin.ajaxReload(msg);
    },'json');
};

/**
 * 解散微吧
 * @param integer weiba_id 微吧ID
 * @return void
 */
admin.delWeiba = function(weiba_id){
    if("undefined" == typeof(weiba_id) || weiba_id=='') weiba_id = admin.getChecked();
    if(weiba_id==''){
        ui.error('請選擇要解散的微吧');return false;
    }  
    if(confirm('確定要解散此微吧嗎？')){
        $.post(U('weiba/Admin/delWeiba'),{weiba_id:weiba_id},function(msg){
            admin.ajaxReload(msg);
        },'json');
    }
};

/**
 * 設定帖子狀態
 * @param integer post_id 帖子ID
 * @param integer type 要設定的帖子類型 1:推薦，2:精華，3:置頂
 * @param integer curValue 當前狀態值
 * @param integer topValue 置頂值，僅置頂用到
 * @return void
 */
admin.setPost = function(post_id, type, curValue, topValue){
    //alert(topValue);exit;
    $.post(U('weiba/Admin/setPost'),{post_id:post_id,type:type,curValue:curValue,topValue:topValue},function(msg){
        admin.ajaxReload(msg);
    },'json');
};

/**
 * 編輯帖子表單驗證
 * @return void
 */
admin.checkEditPost = function(form){
    if(getLength(form.title.value) < 1){
        ui.error('帖子標題不能為空');
        return false;
    }
    if(getLength(form.content.value) < 1){
        ui.error('帖子內容不能為空');
        return false;
    }
    return true;
};

/**
 * 刪除帖子至回收站
 * @param integer post_id 帖子ID
 * @return void
 */
admin.delPost = function(post_id){
    if("undefined" == typeof(post_id) || post_id=='') post_id = admin.getChecked();
    if(post_id==''){
        ui.error('請選擇要刪除的帖子');return false;
    }  
    $.post(U('weiba/Admin/delPost'),{post_id:post_id},function(msg){
        admin.ajaxReload(msg);
    },'json');
};

/**
 * 調整帖子評論樓層
 * @param integer post_id 帖子ID
 * @return void
 */
admin.doStorey = function(post_id){
    if("undefined" == typeof(post_id) || post_id=='') post_id = admin.getChecked();
    if(post_id==''){
        ui.error('請選擇要調整回覆樓層的帖子');return false;
    }  
    $.post(U('weiba/Admin/doStorey'),{post_id:post_id},function(msg){
        if(msg==1){
            ui.success('操作成功');
        }
    });
};

/**
 * 還原已刪除的帖子
 * @param mixed post_id 帖子ID
 * @return void
 */
admin.recoverPost = function(post_id){
    if("undefined" == typeof(post_id) || post_id=='') post_id = admin.getChecked();
    if(post_id==''){
        ui.error('請選擇要還原的帖子');return false;
    }
    $.post(U('weiba/Admin/recoverPost'),{post_id:post_id},function(msg){
            admin.ajaxReload(msg);
        },'json');
};

/**
 * 刪除帖子至回收站
 * @param integer post_id 帖子ID
 * @return void
 */
admin.deletePost = function(post_id){
    if("undefined" == typeof(post_id) || post_id=='') post_id = admin.getChecked();
    if(post_id==''){
        ui.error('請選擇要刪除的帖子');return false;
    }  
    if(confirm('刪除後不可恢復，確定要刪除帖子嗎')){
        $.post(U('weiba/Admin/deletePost'),{post_id:post_id},function(msg){
            admin.ajaxReload(msg);
        },'json');
    }
};

/**
 * 吧主稽覈
 */
admin.doAudit = function(weiba_id, uid, value){
    $.post(U('weiba/Manage/verify'),{weiba_id:weiba_id,uid:uid,value:value},function(msg){
        admin.ajaxReload(msg);
    },'json');
};