/**
 * 取消推薦操作
 * @param integer rowId 資源ID
 * @return void
 */
admin.cancelRecommended = function(rowId)
{
    // 檢查參數
    if(typeof rowId == 'undefined') {
        rowId = admin.getChecked();
        rowId = rowId.join(',');
    }
    if(rowId == '') {
        ui.error('請選擇取消推薦內容');
        return false;
    }
    // 回撥函數
    var unRecommended = function()
    {
        $.post(U('channel/Admin/cancelRecommended'), {rowId:rowId}, function(msg) {
            if(msg.status == 1) {
                ui.success('取消推薦成功');
                location.href = location.href
                return false;
            } else {
                ui.error('取消推薦失敗');
                return false;
            }
        }, 'json');
    };
    if(confirm('確定取消推薦？')) {
        unRecommended();
    }
    return false;
};
/**
 * 駁回操作
 * @param integer rowId 資源ID
 * @return void
 */
admin.rejectChannel = function(rowId)
{
    // 檢查參數
    if(typeof rowId == 'undefined') {
        rowId = admin.getChecked();
        rowId = rowId.join(',');
    }
    if(rowId == '') {
        ui.error('請選擇駁回內容');
        return false;
    }
    // 回撥函數
    var unRecommended = function()
    {
        $.post(U('channel/Admin/cancelRecommended'), {rowId:rowId}, function(msg) {
            if(msg.status == 1) {
                ui.success('駁回成功');
                location.href = location.href;
                return false;
            } else {
                ui.error('駁回失敗');
                return false;
            }
        }, 'json');
    };
    if(confirm('確定駁回？')) {
        unRecommended();
    }
    return false;
};
/**
 * 稽覈操作
 * @param integer rowId 資源ID
 * @return void
 */
admin.auditChannelList = function(rowId, channelId)
{
    var isBatch = false;
    // 檢查參數
    if(typeof rowId == 'undefined') {
        rowId = admin.getChecked();
        rowId = rowId.join(',');
        isBatch = true;
    }
    if(rowId == '') {
        ui.error('請選擇稽覈內容');
        return false;
    }
    // 檢視是否提示編輯彈窗
    if(isBatch) {
        $.post(U('channel/Admin/auditChannelList'), {rowId:rowId}, function(msg) {
            if(msg.status == 1) {
                ui.success('稽覈成功');
                location.href = location.href;
                return false;
            } else {
                ui.error('稽覈失敗');
                return false;
            }
        }, 'json');
    } else {
        // 編輯彈窗
        ui.box.load(U('channel/Admin/editAdminBox'+'&feed_id='+rowId+'&channel_id='+channelId), '編輯頻道');
    }
};
