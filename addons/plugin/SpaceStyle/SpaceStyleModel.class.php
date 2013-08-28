<?php
/**
 * 面板風格模型
 * @author 陳偉川 <258396027@qq.com>
 * @version TS3.0
 */
class SpaceStyleModel extends Model
{
    protected $tableName = 'user_change_style';
    protected $fields = array('uid', 'classname', 'background');
    private $_error = null;     // 最後的錯誤資訊

    /**
     * 獲取最後的錯誤資訊
     * @return string 最後的錯誤資訊
     */
    public function getLastError()
    {
        return $this->_error;
    }

    /**
     * 獲取指定使用者的樣式
     * @param integer $uid 使用者ID
     * @return array 樣式相關資料
     */
    public function getStyle($uid)
    {
        $uid = intval($uid);
        if (!$uid) {
            return false;
        }
        $map = array('uid' => $uid);
        $style_data = $this->where($map)->find();
        $style_data['background'] = unserialize($style_data['background']);
        return $style_data;
    }

    /**
     * 儲存指定使用者的樣式
     * @param integer $uid 使用者ID
     * @param array $style_data 樣式資料
     * @return boolean 是否儲存成功
     */
    public function saveStyle($uid, $style_data)
    {
        $style_data = $this->_escapeStyleData($uid, $style_data);
        if(false === $style_data) {
            return false;
        }
        // 判斷重名
        $map = array('uid'=>$style_data['uid']);
        $uid = $this->getField('uid', $map);
        if($uid > 0) {
            $res = $this->where('uid='.$uid)->save($style_data);
        } else {
            $res = $this->add($style_data);
        }

        if(false !== $res) {
            $this->_error = '設定成功';
            return true;
        } else {
            $this->_error = '設定失敗';
            return false;
        }
    }

    /**
     * 獲取後臺設定的默認樣式
     * @return string 默認樣式Key值
     */
    public function getDefaultStyle()
    {
        $default = model('AddonData')->getAddons('default_style');
        empty($default) && $default = 'default';
        return $default;
    }

    /**
     * 處理資料的合法性
     * @param integer $uid 使用者ID
     * @param array $style_data 樣式相關資料
     * @return mixed 成功返回處理後的資料，失敗返回false
     */
    private function _escapeStyleData($uid, $style_data)
    {
        $_style_data['uid'] = intval($uid);
        $_style_data['classname'] = t($style_data['classname']);
        $_style_data['background'] = $this->_escapeBackgroundData($style_data['background']);
        if($_style_data['uid'] > 0) {
            return $_style_data;
        } else {
            $this->_error = '使用者UID 不合法';
            return false;
        }
    }

    /**
     * 序列化樣式相關資料
     * @param array $background_data 背景相關樣式
     * @return string 序列化樣式相關資料
     */
    private function _escapeBackgroundData($background_data)
    {
        $_backgroup_data['color']  = '';//t($background_data['color']);//暫時無效
        $_backgroup_data['image']  = t($background_data['image']);//圖片檔案
        $_backgroup_data['repeat'] = (in_array($background_data['repeat'],array('repeat','no-repeat')))?t($background_data['repeat']):'';//repeat no-repeat
        $_backgroup_data['attachment'] = (in_array($background_data['attachment'],array('fixed','scroll')))?t($background_data['attachment']):'';//fixed scroll
        $_backgroup_data['position'] = (in_array($background_data['position'],array('top center','top left','top right')))?t($background_data['position']):'';//top center/top left/top right
        return serialize($_backgroup_data);
    }
}
