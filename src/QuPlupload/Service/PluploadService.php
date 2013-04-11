<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Service;

use QuPlupload\Options\PluploadOptions;
use QuPlupload\Entity\PluploadMapperInterface;
use Zend\EventManager\EventManager;
use ZfcBase\EventManager\EventProvider;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;


class PluploadService extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var
     */
    protected $pluploadOptions;

    /**
     * @var
     */
    protected $pluploadModel;

    /**
     * @var
     */
    protected $pluploadEntity;

    /**
     * @var
     */
    protected $pluploadMapper;

    /**
     * @var
     */
    protected $PluploadList;

    /**
     * @var
     */
    protected $ThumbModel;

    /**
     * @var
     */
    protected $RemoveModel;

    /**
     * @var
     */
    protected $serviceManager;



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



    /* UPLOAD!!! */


    /**
     * @param $id_parent
     * @param $data
     * @param $model
     * @return bool
     */
    public function uploadPlupload($id_parent,$data,$model)
    {
       $this->getPluploadOptions();


        $pluploadMapper      = $this->getPluploadMapper();
        $pluploadEntity      = $this->getPluploadEntity();
        $pluploadModel       = $this->getPluploadModel();
        $thumbModel          = $this->getThumbModel();

        $pluploadEntity
        ->setName(      (string) $data['file']['name'])
        ->setType(      (string) $data['file']['type'])
        ->setTmpName(   (string) $data['file']['tmp_name'])
        ->setError(     (int)    $data['file']['error'])
        ->setSize(      (int)    $data['file']['size'])
        ->setIdParent(  (int)    $id_parent)
        ->setModel(     (string) $model)
        ;
        $this->getEventManager()->trigger(__FUNCTION__.'.pre', $this, array('plupload_entity' => $pluploadEntity));

        if(isset($data["chunk"])){

            // UploadModel
            $fileName = $pluploadModel->PluploadModel($data);

            if(($data["chunk"]+1) == $data["chunks"]){


                // Get db last id
                $id = $pluploadMapper->insert($pluploadEntity);


                // Get size and rename
                $fileSize = filesize ( $fileName['filePath'] );
                $NameRename  =  str_replace('/-','/'.$id.'-',$fileName['filePath']);
                rename($fileName['filePath'],$NameRename);


                // Thumb
               $thumbModel->ThumbModel($id.$fileName['fileName']);


                // Update Db
                $pluploadEntity
                    ->setName($id.$fileName['fileName'])
                    ->setSize($fileSize)
                    ->setIdPlupload($id);
                $pluploadMapper->update($pluploadEntity);

            }

        }else{

            // Get db last id
            $id = $pluploadMapper->insert($pluploadEntity);

            // Upload and set Name
            $pluploadModel->setId($id);
            $fileName = $pluploadModel->PluploadModel($data);

            // Thumb
            $thumbModel->ThumbModel($fileName['fileName']);

            // Update Db
            $pluploadEntity
                ->setName($fileName['fileName'])
                ->setIdPlupload($id);
            $pluploadMapper->update($pluploadEntity);

        }

        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('plupload_entity' => $pluploadEntity));


        return true;
    }



    /* REMOVE!! */


    /**
     * @param $id
     * @return bool
     */
    public function PluploadRemove($id)
    {

        $this->getPluploadOptions();

        $pluploadMapper      = $this->getPluploadMapper();
        $RemoveModel         = $this->getRemoveModel();

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('remove_model' => $RemoveModel));


        if($pluploadMapper->find($id)){
            $fileNameDb = $pluploadMapper->find($id)->getName();
            if($RemoveModel->Remove($fileNameDb)){
                $pluploadMapper->Remove($id);
            }
        }

        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('remove_model' => $RemoveModel));

        return true;
    }


    /**
     * @param $model
     * @return bool
     */
    public function pluploadRemoveAll($model)
    {
        $pluploadMapper      = $this->getPluploadMapper();
        $m = $pluploadMapper->findByModel($model,0);
        if( $m ){
            foreach($m as $r){
                $this->PluploadRemove($r->getIdPlupload());
            }
        }
        return true;
    }


    /**
     * @param $id_parent
     * @param $model
     * @return bool
     */
    public function pluploadUpdate($id_parent,$model)
    {
        $pluploadMapper      = $this->getPluploadMapper();
        $pluploadEntity      = $this->getPluploadEntity();
        $m = $pluploadMapper->findByModel($model,0);
        foreach($m as $r){
            $pluploadEntity
                ->setName($r->getName())
                ->setType($r->getType())
                ->setTmpName($r->getTmpName())
                ->setError($r->getError())
                ->setSize($r->getSize())
                ->setIdParent($id_parent)
                ->setModel($r->getModel())
                ->setIdPlupload($r->getIdPlupload());
            $pluploadMapper->update($pluploadEntity);
        }
        return true;
    }


    /* GET LIST!! */





    /**
     * @return mixed
     */
    public function getPluploadList()
    {
        if (!$this->PluploadList) {
            $this->setPluploadIdList(0);
        }
        return $this->PluploadList;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setPluploadIdList($id)
    {
        $this->PluploadList = $this->getPluploadMapper()->findByParent($id);
        return $this;
    }


    /**
     * @return mixed
     */
    public function getPluploadIdAndModelList()
    {
        if (!$this->PluploadList) {
            $this->setPluploadIdList(0);
        }
        return $this->PluploadList;
    }

    /**
     * @param $id
     * @param $model
     * @return $this
     */
    public function setPluploadIdAndModelList($id,$model)
    {
        $this->PluploadList = $this->getPluploadMapper()->findByParentByModel($id,$model);
        return $this;
    }





    /* GET SERVICES */
    /* OPTIONS */





    /**
     * GET OPTIONS
     * @return mixed
     */
    public function getPluploadOptions(){

        if (!$this->pluploadOptions) {
            $this->setPluploadOptions(
                $this->getServiceManager()->get('plupload_options')
            );
        }


        return $this->pluploadOptions;
    }

    /**
     * SET OPTIONS
     * @param PluploadOptions $PluploadOptions
     * @return $this
     */
    public function setPluploadOptions(PluploadOptions $PluploadOptions){

        $this->pluploadOptions = $PluploadOptions;

        return $this;
    }








    /* MAPPER */




    /**
     * GET MAPPER
     * @return mixed
     */
    public function getPluploadMapper()
    {
        if (!$this->pluploadMapper) {
            $this->setPluploadMapper(
                $this->getServiceManager()->get('plupload_mapper')
            );
        }
        return $this->pluploadMapper;
    }

    /**
     * SET MAPPER
     * @param PluploadMapperInterface $pluploadMapper
     * @return $this
     */
    public function setPluploadMapper(PluploadMapperInterface $pluploadMapper)
    {
        $this->pluploadMapper = $pluploadMapper;
        return $this;
    }




    /* MODEL PLUPLOAD */




    /**
     * @return mixed
     */
    public function getPluploadModel(){

        if (!$this->pluploadModel) {
            $this->setPluploadModel(
                $this->getServiceManager()->get('plupload_model')
            );
        }

        if(!$this->DirUpload){
            $this->pluploadModel->setUploadDir($this->getPluploadOptions()->DirUploadAbsolute);
        }


        return $this->pluploadModel;
    }
    /**
     * @param $pluploadModel
     * @return $this
     */
    public function setPluploadModel($pluploadModel){

        $this->pluploadModel = $pluploadModel;
        return $this;
    }



    /* MODEL REMOVE */



    /**
     * @return mixed
     */
    public function getRemoveModel(){

        if (!$this->RemoveModel) {
            $this->setRemoveModel(
                $this->getServiceManager()->get('remove_model')
            );
        }

        if(!$this->DirUpload){
            $this->RemoveModel->setUploadDir($this->getPluploadOptions()->DirUploadAbsolute);
        }
        if(!$this->ThumbResize){
            $this->RemoveModel->setThumbResize($this->getPluploadOptions()->ThumbResize);
        }


        return $this->RemoveModel;
    }

    /**
     * @param $RemoveModel
     * @return $this
     */
    public function setRemoveModel($RemoveModel){
        $this->RemoveModel = $RemoveModel;
        return $this;
    }





    /* MODEL THUMB */



    /**
     * @return mixed
     */
    public function getThumbModel(){

        if (!$this->ThumbModel) {
            $this->setThumbModel(
                $this->getServiceManager()->get('thumb_model')
            );
        }

        if(!$this->DirUpload){
            $this->ThumbModel->setUploadDir($this->getPluploadOptions()->DirUploadAbsolute);
        }
        if(!$this->ThumbResize){
            $this->ThumbModel->setThumbResize($this->getPluploadOptions()->ThumbResize);
        }

        return $this->ThumbModel;
    }

    /**
     * @param $ThumbModel
     * @return $this
     */
    public function setThumbModel($ThumbModel){
        $this->ThumbModel = $ThumbModel;
        return $this;
    }


    /* ENTITY */




    /**
     * @return mixed
     */
    public function getPluploadEntity(){

        if (!$this->pluploadEntity) {
            $this->setPluploadEntity(
                $this->getServiceManager()->get('plupload_entity')
            );
        }
        return $this->pluploadEntity;
    }

    /**
     * @param $pluploadEntity
     * @return $this
     */
    public function setPluploadEntity($pluploadEntity){
        $this->pluploadEntity = $pluploadEntity;
        return $this;
    }




    /* SERVICE MANAGER */




    /**
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}