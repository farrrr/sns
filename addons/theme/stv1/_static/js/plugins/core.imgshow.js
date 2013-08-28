//追加核心圖片展示插件

var imgScroll  = function(id){
	this.id = id;
	this.init();
}; //圖片滾動
var imgTab	   = function(){}; //圖片直接切換	
var imgAnimate = function(){}; //圖片切換（帶移動）

imgScroll.prototype={
	init:function(){
	var id = this.id
	var sWidth = $("#"+id).width(); 
		var len = $("#"+id+" ul li").length; 
		var index = 0;
		var picTimer;

		$("#"+id).find('img').css({'width':sWidth+'px'});

		var btn = "<div class='btn'>";
		for(var i=0; i < len; i++) {
			btn += "<span></span>";
		}
		btn += "</div><div class='move'><div class='preNext pre'></div><div class='preNext next'></div></div>";
		
		$("#"+id).append(btn);
		$('#'+id+' .move').css({'width':sWidth+'px'});
		
		var t = $("#"+id).height()/2 - $('#'+id+' .preNext').height();
		
		$('#'+id+' .preNext').css({'top':t+'px'});

		$("#"+id+" .btnBg").css("opacity",0.5);

		$("#"+id+" .btn span").css("opacity",0.4).mouseenter(function() {
			index = $("#"+id+" .btn span").index(this);
			showPics(index);
		}).eq(0).trigger("mouseenter");

		$("#"+id+"  .preNext").css("opacity",0.8).hover(function() {
			$(this).stop(true,false).animate({"opacity":"0.5"},300);
		},function() {
			$(this).stop(true,false).animate({"opacity":"0.8"},300);
		});

		$("#"+id+"  .pre").click(function() {
			index -= 1;
			if(index == -1) {
				index = len - 1;
			}
			showPics(index);
		});

		$("#"+id+"  .next").click(function() {
			index += 1;
			if(index == len) {index = 0;}
			showPics(index);
		});

		$("#"+id+"  ul").css("width",sWidth * (len));
		
		$("#"+id).hover(function() {
			clearInterval(picTimer);
		},function() {
			picTimer = setInterval(function() {
				showPics(index);
				index++;
				if(index == len) {index = 0;}
			},4000); //´Ë4000´ú±í×Ô¶¯²¥·ÅµÄ¼ä¸ô£¬µ¥Î»£ººÁÃë
		}).trigger("mouseleave");
		
		function showPics(index) { 
			var nowLeft = -index*sWidth; 
			$("#"+id+" ul").stop(true,false).animate({"left":nowLeft},300); 
			$("#"+id+" .btn span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300); //
		}
	}	
};
core.imgshow ={
		//給工廠呼叫的介面
		_init:function(attrs){
			if(attrs.length == 3){

			}else{
				return false;	//只是未了載入檔案
			}
		},
		loginImg:function(t){
			//登入頁面圖片切換
			var sWidth = $(".slide-con").width(); //獲取焦點圖的寬度（顯示面積）
			var len = $(".slide-con ul.slide li").length; //獲取焦點圖個數
			var index = 0;
			var picTimer;
			if("undefined" == typeof(t)){
				t = 4;
			}
			
			$("#slide-title ul li").css("opacity",0.4).mouseenter(function() {
				index = $("#slide-title li").index(this);
				showPics(index);
			}).eq(0).trigger("mouseenter");

			//本例為左右滾動，即所有li元素都是在同一排向左浮動，所以這裡需要計算出外圍ul元素的寬度
			$(".slide-con ul.slide").css("width",sWidth * (len));
			
		    var setPicTimer = function(){
		        picTimer = setInterval(function() {
		            showPics(index);
		            index++;
		            if(index == len) {index = 0;}
		        },t*1000); //此4000代表自動播放的間隔，單位：毫秒
		    };

			//滑鼠滑上焦點圖時停止自動播放，滑出時開始自動播放
			$("#focus").hover(function() {
				clearInterval(picTimer);
		        picTimer = null;
			},function() {
		        setPicTimer();
		    });
		    
			//顯示圖片函數，根據接收的index值顯示相應的內容
			function showPics(index) {
				var nowLeft = -index*sWidth;
				$(".slide-con ul.slide").stop(true,false).animate({"left":nowLeft},300); 
				$("#slide-title li").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300);
		        switch(index) {
		            case 0:
		                $('#focus').addClass('bg-blue');
		                $('#focus').removeClass('bg-black');
		                break;
		            case 1:
		                $('#focus').addClass('bg-black');
		                $('#focus').removeClass('bg-blue');
		                break;
		        }
			}

		    setPicTimer();
		},
		scrollImg:function(id){
			var t = new imgScroll(id);
		}
}		