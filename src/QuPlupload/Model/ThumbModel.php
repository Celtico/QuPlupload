<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Model;

use Zend\Validator\File;

class ThumbModel
{

    /**
     * @var
     */
    protected $uploadDir;

    /**
     * @var
     */
    protected $ThumbResize;


    /**
     * @var
     */
    protected $ThumbService;


    public function ThumbModel($fileName){



        $ThumbSize    = $this->ThumbResize;
        $UploadDir    = $this->uploadDir;
        $file         = $UploadDir . DIRECTORY_SEPARATOR . $fileName;

        $fileTransfer = new File\NotExists($file);
        if(!$fileTransfer->isValid($file)){
            throw new Exception\DomainException(
                sprintf($file.' file not found.')
            );
        }

        //$fileTransfer = new File\IsImage();
        //if($fileTransfer->isValid($file)){
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if($extension == 'jpg' or $extension == 'jpeg' or $extension == 'gif' or $extension == 'png'){

            $ThumbService = $this->ThumbService->create($file);
            foreach($ThumbSize as $key => $size)
            {
                if(!isset($size[1])){
                    throw new Exception\DomainException(
                        sprintf('Config Size not found.')
                    );
                }

                /* Properties  GdThumb Class Definition

                 public function resize ($maxWidth = 0, $maxHeight = 0)
                 public function adaptiveResize ($width, $height)
                 public function resizePercent ($percent = 0)
                 public function cropFromCenter ($cropWidth, $cropHeight = null)
                 public function crop ($startX, $startY, $cropWidth, $cropHeight)

                */

                if($size[0] == 'resize')
                {
                    $ThumbService->resize(@$size[1],@$size[2]);
                }
                elseif($size[0] == 'adaptiveResize')
                {
                    $ThumbService->adaptiveResize(@$size[1],@$size[2]);
                }
                elseif($size[0] == 'resizePercent')
                {
                    $ThumbService->resizePercent(@$size[1]);

                }elseif($size[0] == 'cropFromCenter')
                {
                    $ThumbService->resizePercent(@$size[1]);
                }
                elseif($size[0] == 'crop')
                {
                    $ThumbService->resizePercent(@$size[1],@$size[2],@$size[3],@$size[4]);
                }

                $ThumbService->save(

                        $UploadDir .
                        DIRECTORY_SEPARATOR .
                        $key .
                        $fileName
                );
                @chmod($UploadDir.$size.$fileName, 0777);

            }
        }
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
    public function setThumbResize($ThumbResize)
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
     * @param $ThumbService
     * @return $this
     */
    public function setThumbService($ThumbService)
    {
        $this->ThumbService = $ThumbService;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbService()
    {
        return $this->ThumbService;
    }

}
