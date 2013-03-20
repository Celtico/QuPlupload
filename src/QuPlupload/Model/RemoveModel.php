<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Model;


class RemoveModel
{

    /**
     * @var
     */
    protected $uploadDir;

    /**
     * @var
     */
    protected $ThumbResize;


    public function Remove($fileName){

        $ThumbSize    = $this->ThumbResize;
        $UploadDir    = $this->uploadDir;
        $file         = $UploadDir . DIRECTORY_SEPARATOR . $fileName;

        foreach($ThumbSize as $key => $size)
        {

            $fileSize = $UploadDir . DIRECTORY_SEPARATOR . $key . $fileName;
            if(file_exists($fileSize))
            {
                @unlink($fileSize);
            }

        }

        if(file_exists($file))
        {
            @unlink($file);
            return true;
        }

        return false;

    }

    /**
     * @param $uploadDir
     * @return $this
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
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

}
