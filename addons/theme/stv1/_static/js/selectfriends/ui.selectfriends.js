/**
 * @fileoverview Giant Interective Group, Inc. Javascript Library v#version.
 * 該Javascript UI庫是基於jQuery的擴展。
 * <pre>
 * Copyright (C) 2004-2009 Giant Interective Group, Inc. All rights reserved.
 * 版權所有 2004-2009 上海巨人網路科技有限公司
 * </pre>
 *
 * @version #version, #date
 * @author  Zhangkai
 * Depend on jQuery 1.3.x
 */
if(typeof giant === 'undefined')
        var giant = window.giant = {};
if(typeof giant.ui === 'undefined')
        giant.ui = {};
(function($) {
        /**
	 * 創建一個新的好友選擇器控制項。
	 * 支援多選和單選兩種模式，在單選模式下有回撥函數
	 * @class giant.ui.friendsuggest。Giant UI 的friendsuggest控制項。
	 * @example 
	 * <pre>
     *  &lt;div id=&quot;ui-fs&quot; class=&quot;ui-fs&quot;&gt;
     *      &lt;div class=&quot;ui-fs-result clearfix&quot;&gt;
     *      &lt;/div&gt;
     *      &lt;div class=&quot;ui-fs-input&quot;&gt;
     *       &lt;input type=&quot;text&quot; value=&quot;輸入好友姓名(支援全拼輸入)&quot; maxlength=&quot;$this.opts.ajaxGetCountUrl&quot; /&gt;
     *          &lt;a class=&quot;ui-fs-icon&quot; href=&quot;javascript:void(0)&quot; title=&quot;檢視所有好友&quot;&gt;檢視所有好友&lt;/a&gt;
     *    &lt;/div&gt;
     *    &lt;div class=&quot;ui-fs-list&quot;&gt;
     *        資料載入中....
     *    &lt;/div&gt;
     *      &lt;div class=&quot;ui-fs-all&quot;&gt;
     *        &lt;div class=&quot;top&quot;&gt;
     *            &lt;select id=&quot;ui-fs-friendtype&quot;&gt;&lt;option value=&quot;-1&quot;&gt;所有好友&lt;/option&gt;&lt;/select&gt;
     *             &lt;div class=&quot;close&quot; title=&quot;關閉&quot;&gt;關閉&lt;/div&gt;
     *       &lt;/div&gt;
     *       &lt;div class=&quot;ui-fs-allinner&quot;&gt;
     *           &lt;div class=&quot;page clearfix&quot;&gt;
     *                &lt;div class=&quot;llight1&quot;&gt;還有&lt;b&gt;$this.opts.ajaxGetCountUrl&lt;/b&gt;人可選&lt;/div&gt;&lt;div class=&quot;button&quot;&gt;&lt;span class=&quot;prev&quot;&gt;上一頁&lt;/span&gt;&lt;span class=&quot;next&quot;&gt;下一頁&lt;/span&gt;&lt;/div&gt;
     *             &lt;/div&gt;
     *            &lt;div class=&quot;list clearfix&quot;&gt;
     *                 資料載入中...
     *           &lt;/div&gt;
     *        &lt;/div&gt;
     *   &lt;/div&gt;
     *  &lt;/div&gt;
     * //多選模式--默認
     *  var test = new giant.ui.friendsuggest();
     * //單選模式--
     *   var test2 = new giant.ui.friendsuggest({
     *      totalSelectNum:1,
     *      selectType:"single",
     *      selectCallBack:function(fUid, name, image) {
     *          alert("您選擇的好友ID為"+fUid);
     *          this.setDropDownListHide();
     *          this.setAllFriendHide();
     *      }
     *  });
     *  });
	 * </pre>
	 */

        giant.ui.friendsuggest = function(options) {
                this.opts = $.extend({}, giant.ui.friendsuggest.defaults, options);
                this.currentPage = 1;
                this.totalPage =1;
                this.resultArr = [];
                if(no_edit) var fri_ids = $("#ui_fri_ids").val();
                //alert(fri_ids);
                if(fri_ids) this.resultArr = fri_ids.split(",");
                this.isAllFriendShow = false;   //是否已經顯示所有好友
                this.isDropDownListShow = false; //下拉選項是否已顯示
                this.activeIndex = 0;
                //this.typeId = -1;
                this._init();
        };
        giant.ui.friendsuggest.prototype = {
                _init:function() {
                        this._formartSelect();
                        this._getRecordCound();
                        this._clickBind();
                        this._focusBind();
                        this._blurBind();
                        this._keyUpBind();
                        this._selectChangeBind();
                },
                _formartSelect:function(){
                        var $this= this;
                        $.ajax({
                                url:$this.opts.ajaxGetFriendTypeUrl,
                                success:function(msg) {
                                        var myData = eval(msg);
                                        var arr=[];
                                        $.each(myData,function(i,n){
                                                arr.push("<option value='"+n.id+"'>"+n.name+"</option>");
                                        });
                                        $($this.opts.selectFriendType).append(arr.join(''));
                                }
                        });
                },
                _selectChangeBind:function(){
                        var $this = this;
                        $(this.opts.selectFriendType).bind("change",function(){
                                $this.typeId = $(this).val();
                                $this.currentPage = 1;
                                $this._getRecordCound($this._formartAllFriend);
                        });
                },
                _clickBind:function() {
                        var $this = this;
                        //右側檢視所有小圖示
                        $($this.opts.btnAll).bind("click", function() {
                                if (!$this.isAllFriendShow) {
                                        $this._formartAllFriend();
                                } else {
                                        $this.setAllFriendHide();
                                }

                        });
                        //檢視所有中的關閉按鈕
                        $($this.opts.btnCloseAllFriend).bind("click", function() {
                                $this.setAllFriendHide();
                        });
                        //點選添加好友
                        $($this.opts.allFriendListContainer).find("a").live("click", function() {
                                $this.addUser($(this).attr("name"), $(this).text(), $(this).find("img").attr("src"));
                                $( '.ui-fs-result ' ).css( 'display','block' );
                        });
                        //點選刪除好友
                        $($this.opts.resultContainer).find("span").live("click", function() {
                                $this.deleteUser($(this).parents("a"));
                                if( $this.resultArr == "" ){
                                        $( '.ui-fs-result ' ).css( 'display','none' );
                                }
                        });
                        //下一頁
                        $($this.opts.btnNextPage).bind("click", function() {
                                if ($this.currentPage < $this.totalPage) {
                                        $this.currentPage++;
                                        $($this.opts.allFriendListContainer).html(L('PUBLIC_DATA_LOADING'));
                                        $this._getData($this._getPar(), $($this.opts.allFriendListContainer), null, "all");
                                        if ($this.currentPage == $this.totalPage) {
                                                $($this.opts.btnNextPage).addClass("disable");
                                        }
                                        if ($this.currentPage > 1) {
                                                $($this.opts.btnPrevPage).removeClass("disable");
                                        }
                                }
                        });
                        //上一頁
                        $($this.opts.btnPrevPage).bind("click", function() {
                                if ($this.currentPage > 1) {
                                        $this.currentPage--;
                                        $($this.opts.allFriendListContainer).html(L('PUBLIC_DATA_LOADING'));
                                        $this._getData($this._getPar(), $($this.opts.allFriendListContainer), null, "all");
                                        if ($this.currentPage < $this.totalPage) {
                                                $($this.opts.btnNextPage).removeClass("disable");
                                        }
                                        if ($this.currentPage == 1) {
                                                $($this.opts.btnPrevPage).addClass("disable");
                                        }
                                }
                        });
                },
                _focusBind:function() {
                        var $this = this;
                        $($this.opts.input).bind("focus", function() {
                                $(this).val("");
                                $this.setAllFriendHide();
                                $($this.opts.inputContainer).css("border-color", "#7f9db9");
                        });
                },
                _blurBind:function() {
                        var $this = this;
                        $($this.opts.input).bind("blur", function() {
                                $($this.opts.inputContainer).css("border-color", "#bdc7d8");
                                setTimeout(function() {
                                        $this.setDropDownListHide();
                                }, 200);
                        });
                },
                _keyUpBind:function() {
                        var $this = this;
                        var bind_name = 'input';
                        if (navigator.userAgent.indexOf("MSIE") != -1){
                        	bind_name = 'propertychange';
                        }
                        $(this.opts.input).bind(bind_name, function(event) {
                                if (event.keyCode != "40" && event.keyCode != "38" && event.keyCode != "39" && event.keyCode != "37" && event.keyCode != "13") {
                                        if ($($this.opts.input).val().length > 0) {
                                                $this.setDropDownListShow();
                                                $this._getData("name=" + encodeURIComponent($(this).val()), $($this.opts.dropDownListContainer), function() {
                                                        $($this.opts.dropDownListContainer).find("a").eq(0).addClass("active");
                                                        $($this.opts.dropDownListContainer).find("a").each(function(index) {
                                                                $(this).bind("mouseover", function() {
                                                                        $this.activeIndex = index;
                                                                        $($this.opts.dropDownListContainer).find("a").removeClass("active");
                                                                        $($this.opts.dropDownListContainer).find("a").eq($this.activeIndex).addClass("active");
                                                                });
                                                                $(this).bind("click", function() {
                                                                        var $obj = $($this.opts.dropDownListContainer).find("a.active");
                                                                        $this.addUser($obj.attr("name"), $obj.text(), $obj.find("img").attr("src"));
                                                                        $this.setDropDownListHide();
                                                                        $($this.opts.input).focus();
                                                                         $( '.ui-fs-result ' ).css( 'display','block' );
                                                                });
                                                        });
                                                },"normal");
                                        }
                                        else {       //輸入為空
                                                $($this.opts.dropDownListContainer).html($this.opts.inputDefaultTip);
                                        }
                                }
                                if ($this.isDropDownListShow) {
                                        var totalCount = $($this.opts.dropDownListContainer).find("a").size();
                                        if (totalCount > 0) {
                                                //down 鍵
                                                if (event.keyCode == "40") {
                                                        if ($this.activeIndex < totalCount - 1) $this.activeIndex++;
                                                        else  $this.activeIndex = 0;
                                                }
                                                //up 鍵
                                                else if (event.keyCode == "38") {
                                                        if ($this.activeIndex > 0)  $this.activeIndex--;
                                                        else  $this.activeIndex = totalCount - 1;
                                                }
                                                //回車鍵  加入
                                                else if (event.keyCode == "13") {
                                                        var $obj = $($this.opts.dropDownListContainer).find("a.active");
                                                        $this.addUser($obj.attr("name"), $obj.text(), $obj.find("img").attr("src"));
                                                        $this.setDropDownListHide();
                                                }
                                                $($this.opts.dropDownListContainer).find("a").removeClass("active");
                                                $($this.opts.dropDownListContainer).find("a").eq($this.activeIndex).addClass("active");
                                        }
                                }
                        });
                },
                _formartAllFriend:function() {
                        var $this = this;
                        $this.setAllFriendShow();
                        $this._getData($this._getPar(), $($this.opts.allFriendListContainer), null, "all");
                        //上一頁下一頁判斷
                        if ($this.currentPage == 1) {
                                $($this.opts.btnPrevPage).addClass("disable");
                        }
                        if ($this.currentPage == $this.totalPage || $this.totalPage == 0) {
                                $($this.opts.btnNextPage).addClass("disable");
                        }
                },
                _getData:function(data, $container, callBack, type) {       //全部friend 列表
                        var callBack = callBack || null;
                        var type=type||"normal";
                        var ajaxUrl="";
                        if(type=="normal"){
                                ajaxUrl = this.opts.ajaxUrl;
                        }
                        else{
                                ajaxUrl=this.opts.ajaxLoadAllUrl;
                        }
                        var $this = this;
                        if ($this.xmlHttpObj) {
                                $this.xmlHttpObj.abort();
                                $this.xmlHttpObj = null;
                        }
                        this.xmlHttpObj = $.ajax({
                                url:ajaxUrl,
                                data:data,
                                before:function() {
                                        if ($.isFunction($this.opts.ajaxBefore)) {
                                                $this.opts.ajaxBefore.call($this);
                                        }
                                },
                                success:function(msg) {
                                        var myData = eval(msg);
                                        if (myData && myData.length > 0) {
                                                var arr = [];
                                                $.each(myData, function(i, n) {
                                                        arr.push("<a href='javascript:void(0)' title='" + n.friendUserName + "' name='" + n.fUid + "'><img width='20' height='20' src='" + n.friendHeadPic + "' title='" + n.fUid + "' alt='' />" + n.friendUserName + "</a>");
                                                });
                                                $container.html(arr.join(''));
                                        } else {
                                                $container.html($this.opts.noDataTip);
                                        }
                                        if ($.isFunction(callBack)) {
                                                callBack();
                                        }
                                },
                                error:function() {
                                        if ($.isFunction($this.opts.ajaxError)) {
                                                $this.opts.ajaxError.call($this);
                                        }
                                }
                        });
                },
                _getRecordCound:function(callBack){
                        var $this =this;
                        var data="typeId="+this.typeId;
                        /*if(this.typeId!=-1){
                                data="typeId="+this.typeId;
                        }*/

                        $.ajax({
                                url:$this.opts.ajaxGetCountUrl,
                                data:data,
                                success:function(msg) {
                        				var $ui_fri_num = $("#ui_fri_num");
                                		var fnum = parseInt($ui_fri_num.html());
                                        //改變總好友數
                                		$ui_fri_num.html((fnum < 10)?fnum:'10');
                                        $this.opts.totalSelectNum = (fnum < 10)?fnum:'10';
                                        //end

                                        var myData = eval(msg);
                                        if(!window.isNaN(myData)){
                                                $this.totalPage = Math.ceil(myData/15.0);
                                        }
                                        if($.isFunction(callBack)){
                                                callBack.call($this);
                                        }
                                }
                        });
                },
                _getPar:function() {
                        var data = "pageSize=15&p=" + this.currentPage + "&typeId=" + this.typeId;
                        /*if (this.typeId != -1) {
                                data += "&type=" + this.typeId;
                        }*/
                        return data;
                },
                /**
         * 添加使用者
         * @param{Number} fUid 好友的id
         * @name {String} name 好友的名字
         * @image{String} name 圖片路徑
         * */
                addUser:function(fUid, name, image) {
                        var $this = this;
                        if( parseInt( $("#ui_fri_num").html() )<=0 ){
                        	ui.error( L('PUBLIC_CEND_MAX') );
                        	return ;
                        }
                        //如果當前使用者不存在
                        if ($this.opts.selectType == "multiple") {
                                if ($.inArray(fUid, $this.resultArr) == -1) {
                                        $this.resultArr.push(fUid);
                                        $($this.opts.resultContainer).append("<a href='javascript:void(0)' name='" + fUid + "'><img width='20' height='20' src='" + image + "' title='" + fUid + "' alt='' />" + name + "<span title='移除該好友'></span></a>");
//                                        $($this.opts.frinedNumberContainer).text($this.opts.totalSelectNum- $this.resultArr.length);
                                        var nowTotalSelectNum = '10';
                                        $($this.opts.frinedNumberContainer).text(nowTotalSelectNum - $this.resultArr.length);

                                        var ggid = $this.opts.resultContainer;
                                        var gg_type = ggid.split(" ");
                                        var ggg_type = gg_type[0].substring(6,gg_type[0].length);
                                        $("#ui_fri_ids"+ggg_type).val($this.resultArr);//fantasy添加，貯存好友id
                                } else {
                                        var i = 0;
                                        var $obj = $($this.opts.resultContainer).find("[name='" + fUid + "']");
                                        $obj.css("background-color", "#fff");
                                        //變色
                                        var interval = setInterval(function() {
                                                //IE和FF顏色輸出不一樣
                                                if ($obj.css("background-color") == "#ffffff" || $obj.css("background-color") == "rgb(255, 255, 255)") {
                                                        $obj.css("background-color", "#6699cc");
                                                        $obj.css("color", "#fff");
                                                } else {
                                                        $obj.css("background-color", "#ffffff");
                                                        $obj.css("color", "#666666");
                                                }
                                                i++;
                                                if (i == 4) {
                                                        $obj.attr("style", "");
                                                        clearInterval(interval);
                                                }
                                        }, $this.opts.ajaxGetCountUrl0);
                                }
                        } else {
                                if ($.isFunction($this.opts.selectCallBack)) {
                                        $this.opts.selectCallBack.call($this, fUid, name, image);
                                }
                        }
                },
                deleteUser:function($obj) {
                        var $this = this;
                        $this.resultArr = [];
                        $obj.siblings("a").each(function() {
                                $this.resultArr.push($(this).attr("name"));
                        });
                        $obj.remove();
                        $($this.opts.frinedNumberContainer).text($this.opts.totalSelectNum - $this.resultArr.length);

                        var ggid = $this.opts.resultContainer;
                        var gg_type = ggid.split(" ");
                        var ggg_type = gg_type[0].substring(6,gg_type[0].length);

                        $("#ui_fri_ids"+ggg_type).val($this.resultArr);//fantasy添加，貯存好友id
                },
                /**
         * 顯示好友提示下拉層
         * */
                setDropDownListShow:function() {
                        this.isDropDownListShow = true;
                        this.setAllFriendHide();
                        $(this.opts.dropDownListContainer).show();
                },
                /**
         * 隱藏好友提示下拉層
         * */
                setDropDownListHide:function() {
                        this.isDropDownListShow = false;
                        this.activeIndex = 0;
                        $(this.opts.dropDownListContainer).hide();
                        $(this.opts.input).val("");
                        //$(this.opts.dropDownListContainer).html("");
                },
                /**
         * 顯示所有好友選擇框
         * */
                setAllFriendShow:function() {
                        this.isAllFriendShow = true;
                        this.setDropDownListHide();
                        $(this.opts.allFriendContainer).show();
                        $(this.opts.btnAll).addClass("active");
                },
                /**
         * 隱藏所有好友選擇框
         * */
                setAllFriendHide:function() {
                        this.isAllFriendShow = false;
                        $(this.opts.allFriendContainer).hide();
                        $(this.opts.btnAll).removeClass("active");
                },
                /**
         * 獲取選中的好友結果集
         * @return {Array} 返回存放選中的好友id的陣列
         * */
                getResult:function() {
                        return this.resultArr;
                }
        }
        /**
     * 默認參數
     * <pre>
     * totalSelectNum 多選模式下，最多選取人數，默認為$this.opts.ajaxGetCountUrl
     * selectType 選擇模式，默認為多選"multiple",若為單選，則用single
     * selectCallBack 單選模式下，選中之後的回撥函數。 
     * </pre>
     * */
        giant.ui.friendsuggest.defaults = {
                btnAll:"#ui-fs .ui-fs-icon",
                btnCloseAllFriend:"#ui-fs .ui-fs-all .close",
                btnNextPage:"#ui-fs .ui-fs-all .next",
                btnPrevPage:"#ui-fs .ui-fs-all .prev",
                selectFriendType:"#ui-fs-friendtype",
                allFriendContainer:"#ui-fs .ui-fs-all" ,
                allFriendListContainer:"#ui-fs .ui-fs-all .ui-fs-allinner div.list",
                frinedNumberContainer:"#ui-fs .ui-fs-allinner .page b",
                resultContainer:"#ui-fs .ui-fs-result",
                input:"#ui-fs .ui-fs-input input",
                inputContainer:"#ui-fs .ui-fs-input",
                dropDownListContainer:"#ui-fs .ui-fs-list",
                inputDefaultTip:L('PUBLIC_INPUT_PARENTER'),
                noDataTip:L('PUBLIC_PARENTER_ISNOT'),
                ajaxUrl:"ajax.asp",
                ajaxLoadAllUrl:"ajax.asp",
                ajaxGetCountUrl:"ajaxcount.asp",
                ajaxGetFriendTypeUrl:"ajaxFriendType.asp",
                totalSelectNum:5,
                ajaxBefore:null,
                ajaxError:null,
                selectType:"multiple",
                selectCallBack:null
        };
})(jQuery);
