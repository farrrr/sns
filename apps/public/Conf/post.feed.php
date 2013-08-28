<feed app='public' type='post' info='原創微博'>
    <title comment="feed標題">
        <![CDATA[{$actor}]]>
    </title>
    <body comment="feed詳細內容/引用的內容">
        <![CDATA[{$body|t|replaceUrl} ]]>
    </body>
    <feedAttr comment="true" repost="true" like="false" favor="true" delete="true" />
</feed>
