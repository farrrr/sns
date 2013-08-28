<feed app='weiba' type='weiba_post' info='微吧原創'>
    <title comment="feed標題">
        <![CDATA[ {$actor}  ]]>
    </title>
    <body comment="feed詳細內容/引用的內容">
        <![CDATA[ {$body|t|replaceUrl}
        <php>if(APP_NAME != 'channel'){</php>
        <a href="javascript:void(0)" class="ico-details" event-node ='loadPost' event-args='feed_id={$feedid}&post_id={$app_row_id}' id="{$feedid}"><!--檢視全文--></a><div class="feed_img_lists" style="display:none;" id="post_{$feedid}_{$app_row_id}">
        </div>
        <php>}else{</php>
        <a href="{:U('weiba/Index/postDetail',array('post_id'=>$app_row_id))}" class="ico-details" target="_blank"></a>
        <php>}</php>
        ]]>
    </body>
    <feedAttr comment="true" repost="true" like="false" favor="true" delete="true" />
</feed>
