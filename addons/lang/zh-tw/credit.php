<?php
$creditSet   = array();
//其中 score=>5 表示默認 積分變化為5,其他擴展類型為0
$creditSet['weibo'] = array(
    array('action'=>'add','info'=>'發表微博','score'=>'10','experience'=>'10'),
    array('action'=>'share','info'=>'分享微博','score'=>'10','experience'=>'10'),
    array('action'=>'reply','info'=>'評論微博','score'=>'10','experience'=>'10'),
);
$creditSet['todo'] = array(
    array('action'=>'add','info'=>'添加任務','score'=>'10','experience'=>'10'),
    array('action'=>'finish','info'=>'完成任務','score'=>'10','experience'=>'10'),
);
$creditSet['directory'] = array(
    array('action'=>'add','info'=>'收藏聯繫人','score'=>'10','experience'=>'10'),
);
$creditSet['ask'] = array(
    array('action'=>'add','info'=>'發表問題','score'=>'10','experience'=>'10'),
    array('action'=>'reply','info'=>'回到問題','score'=>'10','experience'=>'10'),
);
$creditSet['doc'] = array(
    array('action'=>'add','info'=>'上傳文件','score'=>'10','experience'=>'10'),
);

return $creditSet;
