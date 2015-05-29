<?php

/**
 * Created by usdk.
 * User: druphliu
 * Date: 14-10-20
 * Time: 下午6:25
 */
class FileUpload
{

    protected $_uploaded = array();
    protected $_destination;
    protected $_max = 2048000000;
    protected $_messages = array();
    protected $_permited = array(
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png'
    );
    protected $_renamed = false;

    /**
     *
     * @param mix $path
     *
     */
    public function __construct($path,$file)
    {
        if(!file_exists($path)){
            mkdir($path,0777,true);
            $handel = fopen($path.'index.php','w');
            fwrite($handel,'');
            fclose($handel);
        }
        if (!is_dir($path) || !is_writable($path)) {
            throw new Exception("文件名不可写，或者不是目录！");
        }
        $this->_destination = $path;
        $this->_uploaded = $file;
    }

    /**
     * 移动文件
     *
     */
    public function move()
    {

        //$filed = current($this->_uploaded);
        $filed = $this->_uploaded;
        $isOk = $this->checkError($filed['name'], $filed['error']);
        //debug ok
        if ($isOk) {
            $sizeOk = $this->checkSize($filed['name'], $filed['size']);
            $typeOk = $this->checkType($filed['name'], $filed['type']);
            if ($sizeOk && $typeOk) {
                $name = md5(time()).substr($filed['name'],strrpos($filed['name'],'.'));
                $success = move_uploaded_file($filed['tmp_name'], $this->_destination . $name);

                if ($success) {
                    $this->_messages['name'] = $name;
                    $this->_messages[] = $filed['name'] . "文件上传成功";
                } else {
                    $this->_messages[] = $filed['name'] . "文件上传失败";
                }
            }

        }
    }

    /**
     * 查询messages数组内容
     *
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     *  检测上传的文件大小
     * @param mix $string
     * @param int $size
     */
    public function checkSize($filename, $size)
    {

        if ($size == 0) {
            return false;
        } else if ($size > $this->_max) {
            $this->_messages[] = "文件超出上传限制大小" . $this->getMaxsize();
            return false;
        } else {
            return true;
        }
    }

    /**
     *  检测上传文件的类型
     * @param mix $filename
     * @param mix $type
     */
    protected function checkType($filename, $type)
    {
        if (!in_array($type, $this->_permited)) {
            $this->_messages[] = "该文件类型是不被允许的上传类型";
            return false;
        } else {
            return true;
        }
    }

    /**
     *  获取文件大小
     *
     */
    public function getMaxsize()
    {
        return number_format($this->_max / 1024, 1) . 'KB';
    }

    /**
     * 检测上传错误
     * @param mix $filename
     * @param int $error
     *
     */
    public function checkError($filename, $error)
    {
        switch ($error) {
            case 0 :
                return true;
            case 1 :
            case 2 :
                $this->_messages[] = "文件过大！";
                return true;
            case 3 :
                $this->_messages[] = "错误上传文件！";
                return false;
            case 4 :
                $this->_messages[] = "没有选择文件!";
                return false;
            default :
                $this->_messages[] = "系统错误!";
                return false;
        }
    }
}