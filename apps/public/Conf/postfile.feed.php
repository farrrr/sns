<feed app='public' type='postfile' info='發附件微博'>
    <title>
        <![CDATA[{$actor}]]>
    </title>
    <body>
        <![CDATA[
            {$body|t|replaceUrl}
            <div>
                <php>if(empty($attachInfo)):</php>
                <ul class="feed_file_list">
                <li>附件已被刪除</li>
                </ul>
                <php>else:</php>
                <ul class="feed_file_list">
                    <volist name='attachInfo' id='vo'>
                        <li><a href="{:U('widget/Upload/down',array('attach_id'=>$vo['attach_id']))}" class="current right" target="_blank" title="下載"><i class="ico-down"></i></a><i class="ico-{$vo.extension}-small"></i><a href="{:U('widget/Upload/down',array('attach_id'=>$vo['attach_id']))}">{$vo.attach_name}</a> <span class="tips">({$vo.size|byte_format})</span></li>
                    </volist>
                    </ul>
                <php>endif;</php>

            </div>
         ]]>
    </body>
    <feedAttr comment="true" repost="true" like="false" favor="true" delete="true" />
</feed>
