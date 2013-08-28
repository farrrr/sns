<?php
$creditSet   = array();
//其中 score=>5 表示默認 積分變化為5,其他擴展類型為0
$creditSet[] = array('action'=>'demo','info'=>'public加積分例子(+)','score'=>'5');
$creditSet[] = array('action'=>'demo_','info'=>'public減積分例子(-)','score'=>'5');
$creditSet[] = array('action'=>'nocredit','info'=>'public積分設定為空的例子(+)','score'=>'0');
return $creditSet;
