/**
 * 頻道核心Js物件
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
var channel = {};
// 用於存儲頻道的配置資訊
channel.setting = {};
/**
 * 頻道初始化
 * $param object option 頻道配置相關資料
 * @return void
 */
channel.init = function(option)
{
    this.setting.container = '#'+option.container;              // 容器ID
    this.setting.loadcount = option.loadcount || 0;             // 載入數目
    this.setting.loadmax = option.loadmax || 4;                 // 載入最大次數
    this.setting.loadId = option.loadId || 0;                   // 載入起始ID
    this.setting.loadlimit = option.loadlimit || 10;            // 每次載入的數目，默認為10
    this.setting.cid = option.cid || 0;                         // 頻道分類ID
    this.setting.canload = option.canload || true;              // 是否能載入
    this.setting.page = 1;                                      // 分頁頁數
    this.setting.newload = 0;                                   // 是否是新載入
    this.setting.categoryJson = option.categoryJson || null;    // 分類JSON資料

    channel.bindScroll();

    if($(channel.setting.container).length > 0 && this.setting.canload){
        $(channel.setting.container).append("<div class='loading' id='loadMore'>" + L('PUBLIC_LOADING') + "<img src='" + THEME_URL + "/image/load.gif' class='load'></div>");
        channel.loadMore();
    }
};

/**
 * 頁面底部觸發事件
 * @return void
 */
channel.bindScroll = function()
{
    // 底部觸發事件繫結
    $(window).bind('scroll resize', function() {
        // 載入指定次數後，將不能自動載入頻道資訊
        if(channel.isLoading()) {
            var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
            var bodyHeight = $(document.body).height();
            if(bodyTop + $(window).height() > bodyHeight - 250) {
                if($(channel.setting.container).length > 0) {
                    // 載入載入樣式
                    $(channel.setting.container).after('<div class="loading" id="loadMore">'+L('PUBLIC_LOADING')+'<img src="'+THEME_URL+'/image/load.gif" class="load"></div>');
                    // 載入資料
                    channel.loadMore();
                }
            }
        }
    });
};
/**
 * 判斷是否頻道時候能自動載入
 * @return boolean 頻道是否能自動載入
 */
channel.isLoading = function()
{
    var status = (this.setting.loadcount >= this.setting.loadmax || this.setting.canload == false) ? false : true;
    return status;
};
/**
 * 獲取載入的資料資訊
 * @return void
 */
channel.loadMore = function()
{
    // 將能載入參數關閉
    channel.setting.canload = false;
    channel.setting.loadcount++;
    // 非同步提交，獲取相關頻道資料
    var postArgs = {};
    postArgs.widget_appname = 'channel';
    postArgs.loadId = channel.setting.loadId;
    postArgs.loadlimit = channel.setting.loadlimit;
    postArgs.loadcount = channel.setting.loadcount;
    postArgs.cid = channel.setting.cid;
    postArgs.p = channel.setting.page;
    postArgs.newload = channel.setting.newload;
    $.get(U('widget/Content/loadMore'), postArgs, function(res) {
        if(res.status == 1) {
            channel.setting.newload = 0;
            // 開啟載入參數
            channel.setting.canload = true;
            // 修改載入ID
            channel.setting.loadId = res.loadId;
            // 動態載入資料
            channel.dynamicLoading(res.html, false);
            // 分頁操作
            if(channel.setting.loadcount >= channel.setting.loadmax) {
                $(channel.setting.container).after('<div id="page" class="page" style="display:none;">'+res.pageHtml+'</div>');
                if($('#page').find('a').size() > 2) {
                    var href = false;
                    $('#page').find('a').each(function() {
                        href = $(this).attr('href');
                    });
                    // 重組分頁結構
                    $('#page').html(res.pageHtml).show();
                    var now = parseInt($('#page').children('.current').html());
                    $('#page').find('a').each(function() {
                        var href = $(this).attr('href');
                        if(href) {
                            $(this).attr('href', '#');
                            $(this).click(function() {
                                $('.boxy-modal-blackout-channel').remove();
                                channel.setting.loadcount = 0;
                                // $(channel.setting.container).replaceWith(channel.setting.cloneContainer);
                                $(channel.setting.container).remove();
                                $('#main-wrap').append('<div id="container" class="mb10 channel-list clearfix"></div>');
                                // $(channel.setting.container) = $(channel.setting.container);     // 容器Jq物件
                                if($(this).is('.pre')) {
                                    channel.setting.page = now - 1;
                                } else if($(this).is('.next')) {
                                    channel.setting.page = now + 1;
                                } else {
                                    channel.setting.page = parseInt($(this).html());
                                }
                                channel.setting.newload = 1;
                                channel.loadMore();
                                $('#page').remove();
                            });
                        }
                    });
                }
            }
        } else {
            $('#loadMore').remove();
            channel.dynamicLoading('', false);
        }
    }, 'json');
    return false;
};
/**
 * 動態載入HTML頻道資料
 * @param DOM html 新載入HTML資料
 * @param boolean page 是否分頁
 * @return void
 */
channel.dynamicLoading = function(html, page)
{
    if(page) {
        html = channel.getCategoryBox() + html;
        $(channel.setting.container).html(html).masonry('reload');
    } else {
        if(channel.setting.loadcount == 1) {
            html = channel.getCategoryBox() + html;
            // 載入瀑布流
            $(channel.setting.container).html(html);
            $(channel.setting.container).masonry({itemSelector: ".box",gutterWidth: 20});
        } else {
            var domDiv = $('<div></div>').append(html);
            var box = [];
            domDiv.find('div').filter('.box').each(function() {
                box.push(this);
            });
            $(channel.setting.container).append($(box)).masonry('appended', $(box));
        }
    }
    $('#loadMore').remove();
    M($(channel.setting.container)[0]);
};
/**
 * 設定分類下拉分類目錄
 * @param string dropId 分類DIV的ID
 * @param string btnId 出發按鈕的ID，暫時沒有使用
 * @return void
 */
channel.setDropBox = function(dropId, btnId)
{
    $('#'+dropId).bind({
        mouseover: function() {
            $(this).addClass('on');
        },
        mouseout: function () {
            $(this).removeClass('on');
        }
    });
};
/**
 * 浮動Fix導航
 * @param string navId 導航ID欄位
 * @return void
 */
channel.setNavigation = function(navId)
{
    var $nav = $('#'+navId);
    var $header = $('#header');
    var height = $header.height();
    $(window).bind('scroll resize', function() {
        var topNav = $nav.offset().top;
        var topHeader = $header.offset().top;
        if(topNav - topHeader < height) {
            $nav.addClass('fixed');
        } else {
            if(topHeader < height) {
                $nav.removeClass('fixed');
            }
        }
    });
};
/**
 * 更改關注頻道分類狀態
 * @param integer uid 關注使用者ID
 * @param integer cid 頻道分類ID
 * @param string type 更新類型，add or del
 * @param object obj 按鈕DOM物件
 * @return void
 */
channel.upFollowStatus = function(uid, cid, type, obj)
{
    // 資料驗證
    if(typeof uid == 'undefined' || typeof cid == 'undefined' || typeof type == 'undefined') {
        return false;
    }
    // 非同步提交處理
    $.post(U('widget/TopMenu/upFollowStatus'), {uid:uid, cid:cid, type:type, widget_appname:'channel'}, function(res) {
        if(res.status == 1) {
            if(type === 'del') {
                ui.success('取消關注成功');
                $(obj).html('<span><i class="ico-add-black"></i>關注</span>');
                $(obj).attr('onclick', "channel.upFollowStatus('"+uid+"', '"+cid+"', 'add', this)");
                channel.upFigures(false);
            } else if(type === 'add') {
                ui.success('關注成功');
                $(obj).html('<span><i class="ico-already"></i>已關注</span>');
                $(obj).attr('onclick', "channel.upFollowStatus('"+uid+"', '"+cid+"', 'del', this)");
                channel.upFigures(true);
            }
        } else {
            ui.error('關註失敗');
        }
    }, 'json');
    return false;
};
/**
 * 投稿彈窗
 * @param integer cid 頻道分類ID
 * @return void
 */
channel.contributeBox = function(cid)
{
    ui.box.load(U('channel/Index/contributeBox')+'&cid='+cid, '我要投稿');
    return false;
};
/**
 * 獲取頻道分類資料瀑布流塊
 * @return string 頻道分類資料瀑布流塊
 */
channel.getCategoryBox = function()
{
    var html = '<div class="box boxShadow">\
    <div class="channel-tab-menu"><dl><dt>';
    var data = $.parseJSON(channel.setting.categoryJson);
    for(var i in data) {
        html += '<a class="btn-cancel '+((data[i]['channel_category_id'] == channel.setting.cid) ? 'current' : '')+'" href="'+U('channel/Index/index')+'&cid='+data[i]['channel_category_id']+'"><span>'+data[i]['title']+'</span></a>';
    }
    html += '</dt></dl></div></div>';

    return html;
};
/**
 * 更新頻道關注數目
 * @param boolean inc 更新類型，增加還是減少
 * @return void
 */
channel.upFigures = function(inc)
{
    inc = (typeof inc === 'undefined') ? true : inc;
    var nums = parseInt($('#channel_follow_nums').html());
    if(inc) {
        nums++;
    } else {
        nums--;
    }
    nums = (nums < 0 ) ? 0 : nums;
    $('#channel_follow_nums').html(nums);
};
/**
 * 管理彈窗顯示
 * @param integer feedId 微博ID
 * @param integer channelId 頻道分類ID
 * @return void
 */
var getAdminBox = function(feedId, channelId)
{
    ui.box.load(U('channel/Manage/getAdminBox')+'&feed_id='+feedId+'&channel_id='+channelId, '推薦到頻道');
};
