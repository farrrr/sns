/*
 * 頭像上傳
 *
 * @param object args:
 * {
 * 	   
 * }
 *
 *
 */
var avatar = function( args ) {
		// 上傳表單
	var uploadForm = args.uploadForm,
		uploadBtn = args.uploadBtn,
		loading = args.loading,

		// 瀏覽頭像
		scanImg = args.scanImg,

		// 設定表單
	    settingForm = args.settingForm,
	    picUrl = args.picUrl,
	    picWidth = args.picWidth,
	    area = args.area,
	    preview = args.preview,
	    saveBtn = args.saveBtn,
	    resetBtn = args.resetBtn,
	    saveTip = args.saveTip || "Sure to save photo?",

		// 資料
	    selectEnd = args.selectEnd,

		up_pic_width = 200,
		up_pic_height = 200,

		imgrs,

		tmpDate = new Date(),

		// 呼叫jQuery 插件所需
		$area,
		$area_img,
		$preview_img,

		set_UP_W_H = function( w, h ){
			// up_pic_width = w;
			// up_pic_height = h;
			up_pic_width = 300;
			up_pic_height = 300*h/w;
		},

		onSelectEnd = function( img, selection ) {
		    selectEnd["avatarX1"].value = selection.x1;
		    selectEnd["avatarY1"].value = selection.y1;
		    selectEnd["avatarX2"].value = selection.x2;
		    selectEnd["avatarY2"].value = selection.y2;
		    selectEnd["avatarW"].value = selection.width;
		    selectEnd["avatarH"].value = selection.height;
        },

		previewFn = function( img, selection ) {
        	onSelectEnd( img, selection );
		    var big_scaleX = 200 / ( selection.width || 1 ),
		    	big_scaleY = 200 / ( selection.height || 1 );

		    $preview_img.css({
		        width: Math.round( big_scaleX * up_pic_width ) + 'px',
		        height: Math.round( big_scaleY * up_pic_height ) + 'px',
		        marginLeft: '-' + Math.round( big_scaleX * selection.x1 ) + 'px',
		        marginTop: '-' + Math.round( big_scaleY * selection.y1 ) + 'px'
		    });
        };

	// 頭像上傳 [選擇圖片後自動上傳]
	uploadBtn.onchange = function() {
		//檔案類型檢驗
		var checkFile=function(){
			var filename = $(uploadBtn).val();
			var pos = filename.lastIndexOf(".");  
		    var str = filename.substring(pos, filename.length)  
		    var str1 = str.toLowerCase();  
		    if (!/\.(gif|jpg|jpeg|png)$/.test(str1)) {  
		    	uploadBtn.value = '';
		    	$(uploadBtn).val('');
		        return false;  
		    } 
		    return true;
		};

		if(!checkFile()){
			ui.error( L('PUBLIC_UPDATE_TYPE_TIPS') );
			return false;
		}

	    uploadBtn.style.display = "none";
	    loading.style.display = "block";
		// 非同步提交頭像
		
		//M.getJS( THEME_URL + "/js/jquery.form.js?"+SYS_VERSION, function() {
            var options = {
                success: function( txt ) {
                	txt = eval("(" + txt + ")");
                	if ( 1 == txt.status ) {
                		// 頭像切割
                		M.getCSS( THEME_URL + "/js/imgareaselect/css/imgareaselect-default.css" );
                		M.getJS( THEME_URL + "/js/imgareaselect/jquery.imgareaselect.min.js", function() {
				            set_UP_W_H(txt.data["picwidth"],txt.data["picheight"]);
				            picUrl.value = txt.data["picurl"];
				            picWidth.value = txt.data["picwidth"];
				            area.innerHTML = "<img width=300 src=\"" + txt.data["fullpicurl"] + "?t=" + tmpDate.getTime() + "\" />";
				            preview.innerHTML = "<img width=300 src=\"" + txt.data["fullpicurl"] + "?t=" + tmpDate.getTime() + "\" />";

				            $area = $( area );
							$area_img = $( area.getElementsByTagName("img")[0] );
							$preview_img = $( preview.getElementsByTagName("img")[0] );

				            imgrs = $area_img.imgAreaSelect({ 
		                        x1: 0, 
		                        y1: 0,
		                        x2: 200, //初始矩形寬
		                        y2: 200, //初始矩形高
		                        handles: true,
		                        onInit: previewFn,
		                        onSelectChange: previewFn,
		                        onSelectEnd: onSelectEnd,
		                        aspectRatio: '1:1',	//頭像長寬比
		                        instance: true,
		                        parent: $area
		                    });
				            // 隱藏上傳表單，顯示設定表單
						    uploadForm.style.display = "none";
						    settingForm.style.display = "block";
                		});
						uploadBtn.value = '';
		    			$(uploadBtn).val('');
                	} else {
                		ui.error(txt.info);
                	}
                	uploadBtn.style.display = "block";
                	loading.style.display = "none";
					uploadForm.reset();
                }
            };
            $( uploadForm ).ajaxSubmit( options );
		// });
		return false;
	};

	// 頭像儲存 [點選]
	saveBtn.onclick = function() {
		var args = M.getEventArgs(this);

		// if ( ! confirm( saveTip ) ) {
		// 	return false;
		// }
		// M.getJS( THEME_URL + "/js/jquery.form.js", function() {
	        var options = {
                success: function( txt ) {
                	//fuck 迅雷. 居然自動在程式返回值中加html程式碼
                	txt = strip_tags(txt);
				    txt = eval("(" + txt + ")");
				    if ( 1 == txt.status ) {
				    	var l = scanImg.length,
				    		time = tmpDate.getTime();
				    	while ( l -- > 0) {
				    		switch ( scanImg[l].size ) {
				    			case "big":
				    				scanImg[l].img.src = txt.data.big + "?t=" + time;
				    				break;
				    			case "middle":
				    				scanImg[l].img.src = txt.data.middle + "?t=" + time;
				    				break;
				    			case "small":
				    				scanImg[l].img.src = txt.data.small + "?t=" + time;
				    				break;
				    			default:
				    				;
				    		}
				    	}
				    } else {
				        ui.error( txt.info );
				    }
				    avatar_success();
		            // 顯示上傳表單，隱藏設定表單
				    settingForm.style.display = "none";
				    uploadForm.style.display = "block";
                }
            };
	        $( settingForm ).ajaxSubmit( options );
		// });
		return false;
	};

	// 頭像重置 [點選]
	resetBtn.onclick = function() {
        // 顯示上傳表單，隱藏設定表單
        uploadBtn.value = '';
        $(uploadBtn).val('');
	    settingForm.style.display = "none";
	    uploadForm.style.display = "block";
	    return false;
	}
};