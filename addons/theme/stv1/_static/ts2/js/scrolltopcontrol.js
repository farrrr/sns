var scrolltotop={
	//startline: 滑鼠向下滾動了100px後出現#topcontrol
	//scrollto: 它的值可以是整數，也可以是一個id標記。若為整數（假設為n），則滑動到距離top的n畫素處；若為id標記，則滑動到該id標記所在的同等高處
	//scrollduration:滑動的速度
	//fadeduration:#topcontrol這個div的淡入淡出速度，第一個參數為淡入速度，第二個參數為淡出速度
	//controlHTML:控制向上滑動的html源碼，默認為<img src="up.png" style="width:48px; height:48px" />，可以自行更改。該處的html程式碼會被包含在一個id標記為#topcontrol的div中。
	//controlattrs:控制#topcontrol這個div距離右下角的畫素距離
	//anchorkeyword:滑動到的id標籤
	/*state: isvisible:是否#topcontrol這個div為可見
			shouldvisible:是否#topcontrol這個div該出現
	*/

	setting: {startline:100, scrollto: 0, scrollduration:500, fadeduration:[500, 100]},
	controlHTML: '<a href="#top" class="top_stick">&nbsp;</a>',
	controlattrs: {offsetx:20, offsety:30},
	anchorkeyword: '#top',

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) {
			this.$control.css({opacity:0})
		};//點選後隱藏#topcontrol這個div
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto);
		if (typeof dest=="string" && jQuery('#'+dest).length==1) { //檢查若scrollto的值是一個id標記的話
			dest=jQuery('#'+dest).offset().top;
		} else { //檢查若scrollto的值是一個整數
			dest=this.setting.scrollto;
		};
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		//獲得瀏覽器的視窗物件
		var $window=jQuery(window);
		//獲得#topcontrol這個div的x軸座標
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx;
		//獲得#topcontrol這個div的y軸座標
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety;
		//隨著滑動塊的滑動#topcontrol這個div跟隨著滑動
		this.$control.css({left:controlx+'px', top:controly+'px'});
	},

	togglecontrol:function(){
		//當前視窗的滑動塊的高度
		var scrolltop=jQuery(window).scrollTop();
		if (!this.cssfixedsupport) {
			this.keepfixed();
		};
		//若設定了startline這個參數，則shouldvisible為true
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false;
		//若shouldvisible為true，且!isvisible為true
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0]);
			this.state.isvisible=true;
		} //若shouldvisible為false，且isvisible為false
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1]);
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop;
			var iebrws=document.all;
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest; //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body');

			//包含#topcontrol這個div
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
				.attr({title:'返回頂部'})
				.click(function(){mainobj.scrollup(); return false;})
				.appendTo('body');

			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') {//loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}); //IE6- seems to require an explicit width on a DIV containing text
			};

			mainobj.togglecontrol();

			//點選控制
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup();
				return false;
			});

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			});
		});
	}
};

scrolltotop.init();