<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Options;

use Zend\Stdlib\AbstractOptions;

class PluploadOptions extends AbstractOptions
{

    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $DirUploadAbsolute;

    /**
     * @var
     */
    protected $ThumbResize;

    /**
     * @var
     */
    protected $Resize;

    /**
     * @var
     */
    protected $DirUpload;


    /**
     * @param $tableName
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param $DirUploadAbsolute
     * @return $this
     */
    public function setDirUploadAbsolute($DirUploadAbsolute)
    {
        $this->DirUploadAbsolute = $DirUploadAbsolute;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirUploadAbsolute()
    {
        return $this->DirUploadAbsolute;
    }

    /**
     * @param array $ThumbResize
     * @return $this
     */
    public function setThumbResize(array $ThumbResize)
    {
        $this->ThumbResize = $ThumbResize;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbResize()
    {
        return $this->ThumbResize;
    }


    /**
     * @param array $Resize
     * @return $this
     */
    public function setResize(array $Resize)
    {
        $this->Resize = $Resize;
        return $this;
    }

    /**
     * @return string
     */
    public function getResize()
    {
        return $this->Resize;
    }

    /**
     * @param $DirUpload
     * @return $this
     */
    public function setDirUpload($DirUpload)
    {
        $this->DirUpload = $DirUpload;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirUpload()
    {
        return $this->DirUpload;
    }



    /**
     * @package unfinished
     * @todo finish config parameters Plupload
     */
}
