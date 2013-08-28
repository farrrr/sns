/*
 * json2select
 *
 * Copyright (c) 2008 Shawphy (shawphy.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/*
 * Create selects from JSON
 * 
 * @example $("#selectt").json2select( json, dft, name, deep);
 * @desc 在#selectt中通過d創建一組關聯的select
 *
 * @param json,格式如下
 *   var json=[
 *   	{
 *   		t:"歐洲某地",
 *   		a:"歐洲"
 *   	},
 *   	{
 *   		t:"中國某地",
 *   		a:"中國",
 *   		d:[
 *   			{
 *   				t:"上海",
 *   				a:"上海"
 *   			},
 *   			{
 *   				t:"雲南某地",
 *   				a:"雲南某地",
 *   				d:[
 *   					{
 *   						t:"大理",
 *   						a:"大理"
 *   					}
 *   				]
 *   			}
 *   		]
 *   	},
 *   	{
 *   		t:"日本某地",
 *   		a:"日本",
 *   		d:[
 *   			{
 *   				t:"東京",
 *   				a:"東京"
 *   			},
 *   			{
 *   				t:"北海道",
 *   				a:"北海道",
 *   				d:[
 *   					{
 *   						t:"北海道的某個地方",
 *   						a:"北海道的某個地方"
 *   					}
 *   				]
 *   			}
 *   		]
 *   	}
 *   ];
 * @param dft,陣列，設定預設值，如["中國","雲南","大理"]
 * @param name,字元串，預設值：sel，用於設定select的name的字首
 * @param deep,整形數字，預設值：0，用於設定初始的深度，如設定為0，則第一個select的name屬性就是sel0
 * @return 呼叫它的物件
 * @type jQuery物件
 *
 */

;(function($) {
$.fn.json2select=function(json,dft,name,deep,css) {
	//參數初始化
	var _this=this,				//儲存呼叫的物件
		name=name||"sel",		//如果未提供名字，則為默認為sel
		deep=deep||0,			//深度，默認為0，即生成的select的name=sel0
		dft=dft||[],			//預設值
		css=css||'height: 150px; width: 140px;';
	//換內容的時候刪除舊的select
	$("[name="+name+deep+"]",_this).nextAll().remove();
	if (json[0]) {
		//新建一個select
		var slct=$("<select name='"+name+$("select",_this).length+"' id='"+name+$("select",_this).length+"' style='"+css+"' size='10'></select>");
		//建立一個默認項，value為空，修改請保留為空
		$.each(json,function(i,sd) {
			//添加項目，並用data將其子元素附加在這個option上以備後用。
			if(i == 0){
				$("<option value='"+sd.a+"' selected='selected' id='xxx'>"+sd.t+"</option>").appendTo(slct).data("d",sd.d||[]);
			}else {
				$("<option value='"+sd.a+"' >"+sd.t+"</option>").appendTo(slct).data("d",sd.d||[]);
			}
		});

		$("#xxx").select(); 
		//繫結這個select的change事件
		slct.change(function(e,dftflag) {
			//如果選的不是value為空的，則呼叫方法本身。如果已經初始化過了,即，不是由trigger觸發的，而是手工點的，則不將dft傳遞進去。
			$(this).val()&&_this.json2select($(":selected",this).data("d"),dftflag?dft.slice(1):[],name,$(this).attr("name").match(/\d+/)[0]);
			//設定初始值，並且觸發change事件，傳遞true參數進去。
		}).appendTo(_this).val(dft[0]||0).trigger("change",[true]);
	}
	//返回jQuery物件
	return _this;
};
})(jQuery);