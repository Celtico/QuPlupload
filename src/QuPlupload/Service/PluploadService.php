<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Service;

use QuPlupload\Options\PluploadOptions;
use QuPlupload\Entity\PluploadMapperInterface;
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




    /* UPLOAD!!! */




    /**
     * @param $id_parent
     * @param $data
     * @return bool
     */
    public function uploadPlupload($id_parent,$data)
    {

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
        ;

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('plupload_entity' => $pluploadEntity));

                // Get db last id
                $id = $pluploadMapper->insert($pluploadEntity);

                // Upload
                $pluploadModel->setId($id);

                // Get Name
                $fileNameDb = $pluploadModel->PluploadModel($data);

                // Thumb
                $thumbModel->ThumbModel($fileNameDb);

                // Update Db
                $pluploadEntity->setName($fileNameDb)->setIdPlupload($id);
                $pluploadMapper->update($pluploadEntity);

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
        $pluploadMapper      = $this->getPluploadMapper();
        $RemoveModel         = $this->getRemoveModel();

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('remove_model' => $RemoveModel));

            $fileNameDb = $pluploadMapper->find($id)->getName();
            if($RemoveModel->Remove($fileNameDb)){
                $pluploadMapper->Remove($id);
            }

        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('remove_model' => $RemoveModel));

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