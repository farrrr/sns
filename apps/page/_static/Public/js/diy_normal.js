$(function(){
    var iebrws = document.all;
    var cssfixedsupport = !iebrws || iebrws && document.compatMode == "CSS1Compat" && window.XMLHttpRequest;
    var id = "openDiy";
    
    if (cssfixedsupport) {
        //$('#themeheader').css('margin-top', ($('#' + id).height() + 10) + 'px');
        $('#' + id).css('position', 'fixed').css('right', '120px').css('top','0px');
    }else{
		$('#' + id).css('position', 'absolute').css('right','120px').css('top','0px');
	}
    $('#' + id).css('z-index' , 1000);
    $(window).bind('scroll resize', function(e){
        if (!cssfixedsupport) {
            keepfixed(id);
        }
    });
	$('#'+id).show();
})

function keepfixed(id){
    //獲得瀏覽器的視窗物件
    var $window = jQuery(window);
    //獲得#topcontrol這個div的x軸座標
    //獲得#topcontrol這個div的y軸座標
    var controly = $window.scrollTop();
    //隨著滑動塊的滑動#topcontrol這個div跟隨著滑動
    $('#'+id).css({
        right: '120px',
        top: controly + 'px'
    });
}