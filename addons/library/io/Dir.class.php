<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * DirectoryIterator實現類 PHP5以上內建了DirectoryIterator類
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Io
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Dir implements IteratorAggregate
{//類定義開始

    private $_values = array();
    /**
     +----------------------------------------------------------
     * 架構函數
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $path  目錄路徑
     +----------------------------------------------------------
     */
    function __construct($path,$pattern='*')
    {
        if(substr($path, -1) != "/")    $path .= "/";
        $this->listFile($path,$pattern);
    }

    /**
     +----------------------------------------------------------
     * 取得目錄下面的檔案資訊
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $pathname 路徑
     +----------------------------------------------------------
     */
    function listFile($pathname,$pattern='*')
    {
        static $_listDirs = array();
        $guid   =   md5($pathname.$pattern);
        if(!isset($_listDirs[$guid])){
            $dir = array();
            $list   =   glob($pathname.$pattern);
            foreach ($list as $i=>$file){
                //$dir[$i]['filename']    = basename($file);
                //basename取中文名出問題.改用此方法
                //編碼轉換.把中文的調整一下.
                $dir[$i]['filename']     = preg_replace('/^.+[\\\\\\/]/', '', $file);
                $dir[$i]['pathname']     = realpath($file);
                $dir[$i]['owner']        = fileowner($file);
                $dir[$i]['perms']        = fileperms($file);
                $dir[$i]['inode']        = fileinode($file);
                $dir[$i]['group']        = filegroup($file);
                $dir[$i]['path']         = dirname($file);
                $dir[$i]['atime']        = fileatime($file);
                $dir[$i]['ctime']        = filectime($file);
                $dir[$i]['size']         = filesize($file);
                $dir[$i]['type']         = filetype($file);
                $dir[$i]['ext']          = is_file($file)?strtolower(substr(strrchr(basename($file), '.'),1)):'';
                $dir[$i]['mtime']        = filemtime($file);
                $dir[$i]['isDir']        = is_dir($file);
                $dir[$i]['isFile']       = is_file($file);
                $dir[$i]['isLink']       = is_link($file);
                //$dir[$i]['isExecutable']= function_exists('is_executable')?is_executable($file):'';
                $dir[$i]['isReadable']    = is_readable($file);
                $dir[$i]['isWritable']    = is_writable($file);
            }
            $cmp_func = create_function('$a,$b','
                $k  =  "isDir";
            if($a[$k]  ==  $b[$k])  return  0;
            return  $a[$k]>$b[$k]?-1:1;
            ');
            // 對結果排序 保證目錄在前面
            usort($dir,$cmp_func);
            $this->_values = $dir;
            $_listDirs[$guid] = $dir;
        }else{
            $this->_values = $_listDirs[$guid];
        }
    }

    /**
     +----------------------------------------------------------
     * 檔案上次訪問時間
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getATime()
    {
        $current = $this->current($this->_values);
        return $current['atime'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的 inode 修改時間
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getCTime()
    {
        $current = $this->current($this->_values);
        return $current['ctime'];
    }

    /**
     +----------------------------------------------------------
     * 遍歷子目錄檔案資訊
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return DirectoryIterator
     +----------------------------------------------------------
     */
    function getChildren()
    {
        $current = $this->current($this->_values);
        if($current['isDir']){
            return new Dir($current['pathname']);
        }
        return false;
    }

    /**
     +----------------------------------------------------------
     * 取得檔名
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function getFilename()
    {
        $current = $this->current($this->_values);
        return $current['filename'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的組
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getGroup()
    {
        $current = $this->current($this->_values);
        return $current['group'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的 inode
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getInode()
    {
        $current = $this->current($this->_values);
        return $current['inode'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的上次修改時間
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getMTime()
    {
        $current = $this->current($this->_values);
        return $current['mtime'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的所有者
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function getOwner()
    {
        $current = $this->current($this->_values);
        return $current['owner'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案路徑，不包括檔名
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function getPath()
    {
        $current = $this->current($this->_values);
        return $current['path'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的完整路徑，包括檔名
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function getPathname()
    {
        $current = $this->current($this->_values);
        return $current['pathname'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的許可權
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getPerms()
    {
        $current = $this->current($this->_values);
        return $current['perms'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案的大小
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     */
    function getSize()
    {
        $current = $this->current($this->_values);
        return $current['size'];
    }

    /**
     +----------------------------------------------------------
     * 取得檔案類型
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function getType()
    {
        $current = $this->current($this->_values);
        return $current['type'];
    }

    /**
     +----------------------------------------------------------
     * 是否為目錄
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    function isDir()
    {
        $current = $this->current($this->_values);
        return $current['isDir'];
    }

    /**
     +----------------------------------------------------------
     * 是否為檔案
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    function isFile()
    {
        $current = $this->current($this->_values);
        return $current['isFile'];
    }

    /**
     +----------------------------------------------------------
     * 檔案是否為一個符號連線
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    function isLink()
    {
        $current = $this->current($this->_values);
        return $current['isLink'];
    }


    /**
     +----------------------------------------------------------
     * 檔案是否可以執行
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    function isExecutable()
    {
        $current = $this->current($this->_values);
        return $current['isExecutable'];
    }


    /**
     +----------------------------------------------------------
     * 檔案是否可讀
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    function isReadable()
    {
        $current = $this->current($this->_values);
        return $current['isReadable'];
    }

    /**
     +----------------------------------------------------------
     * 獲取foreach的遍歷方式
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function getIterator()
    {
        return new ArrayObject($this->_values);
    }

    // 返回目錄的陣列資訊
    function toArray() {
        return $this->_values;
    }

    // 靜態方法

    /**
     +----------------------------------------------------------
     * 判斷目錄是否為空
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    function isEmpty($directory)
    {
        $handle = opendir($directory);
        while (($file = readdir($handle)) !== false)
        {
            if ($file != "." && $file != "..")
            {
                closedir($handle);
                return false;
            }
        }
        closedir($handle);
        return true;
    }

    /**
     +----------------------------------------------------------
     * 取得目錄中的結構資訊
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    function getList($directory)
    {
        return scandir($directory);
    }

    /**
     +----------------------------------------------------------
     * 刪除目錄（包括下面的檔案）
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    function delDir($directory,$subdir=true)
    {
        if (is_dir($directory) == false)
        {
            exit("The Directory Is Not Exist!");
        }
        $handle = opendir($directory);
        while (($file = readdir($handle)) !== false)
        {
            if ($file != "." && $file != "..")
            {
                is_dir("$directory/$file")?
                    Dir::delDir("$directory/$file"):
                    unlink("$directory/$file");
            }
        }
        if (readdir($handle) == false)
        {
            closedir($handle);
            rmdir($directory);
    }
    }

    /**
     +----------------------------------------------------------
     * 刪除目錄下面的所有檔案，但不刪除目錄
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    function del($directory)
    {
        if (is_dir($directory) == false)
        {
            exit("The Directory Is Not Exist!");
    }
    $handle = opendir($directory);
    while (($file = readdir($handle)) !== false)
    {
        if ($file != "." && $file != ".." && is_file("$directory/$file"))
        {
            unlink("$directory/$file");
    }
    }
    closedir($handle);
    }

    /**
     +----------------------------------------------------------
     * 複製目錄
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    function copyDir($source, $destination)
    {
        if (is_dir($source) == false)
        {
            exit("The Source Directory Is Not Exist!");
    }
    if (is_dir($destination) == false)
    {
        mkdir($destination, 0700);
    }
    $handle=opendir($source);
    while (false !== ($file = readdir($handle)))
    {
        if ($file != "." && $file != "..")
        {
            is_dir("$source/$file")?
                Dir::copyDir("$source/$file", "$destination/$file"):
                copy("$source/$file", "$destination/$file");
    }
    }
    closedir($handle);
    }

    }//類定義結束

    if(!class_exists('DirectoryIterator')) {
        class DirectoryIterator extends Dir {}
    }
?>
