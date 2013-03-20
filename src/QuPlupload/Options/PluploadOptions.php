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
     * @package unfinished
     * @todo finish config parameters Plupload
     */
}
