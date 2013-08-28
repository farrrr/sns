<?php
// +----------------------------------------------------------------------
// | OpenSociax [ Social business software! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.zhishisoft.com All rights reserved.
// +----------------------------------------------------------------------
// | Sociax Commercial Licensed
// +----------------------------------------------------------------------
// | Author: liuxiaoqing@zhishisoft.com
// +----------------------------------------------------------------------
// $Id$

/**
 * 簡潔Modelite介面抽象類
 * @category    core
 * @package     OpenSociax
 * @author      liuxiaoqing <liuxiaoqing@thinksns.com>
 * @version     $0.1$
 */
abstract class Modelite {

    // 最近錯誤資訊
    protected $error = '';

    /**
     * 架構函數
     * 取得DB類的例項物件 欄位檢查
     * @param string $name 模型名稱
     * @access public
     */
    public function __construct($name='') {
        // 模型初始化
        $this->_initialize();
    }

    // 回撥方法 初始化模型
    protected function _initialize() {}

        // 獲取最近的錯誤資訊
        public function getError() {
            return $this->error;
        }
}
?>
