<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */
namespace QuPlupload;

use QuPlupload\View\Helper\PluploadHelp;
use QuPlupload\View\Helper\PluploadHelpLoad;

class Module
{

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'plupload_service'  => 'QuPlupload\Service\PluploadService',
                'plupload_entity'   => 'QuPlupload\Entity\PluploadEntity',
            ),
            'factories' => array(

                'plupload_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\PluploadOptions(
                        isset($config['QuConfig']['QuPlupload']) ? $config['QuConfig']['QuPlupload'] : array()
                    );
                },

                'plupload_model' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $PluploadModel = new Model\PluploadModel();
                    $PluploadModel->setUploadDir($options->getDirUploadAbsolute());
                    return $PluploadModel;
                },

                'plupload_mapper' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $mapper  = new Entity\PluploadMapper();
                    $mapper->setTableName($options->getTableName());
                    $mapper->setDbAdapter($sm->get('plupload_adapter'));
                    $mapper->setEntityPrototype($sm->get('plupload_entity'));
                    $mapper->setHydrator(new Entity\PluploadHydrator());
                    return $mapper;
                },


                'thumb_model' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $ThumbModel = new Model\ThumbModel();
                    $ThumbModel->setUploadDir($options->getDirUploadAbsolute());
                    $ThumbModel->setThumbResize($options->getThumbResize());
                    $ThumbModel->setThumbService($sm->get('WebinoImageThumb'));
                    return $ThumbModel;
                },

                'remove_model' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $RemoveModel = new Model\RemoveModel();
                    $RemoveModel->setUploadDir($options->getDirUploadAbsolute());
                    $RemoveModel->setThumbResize($options->getThumbResize());
                    return $RemoveModel;
                },
            ),
        );
    }


    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'PluploadHelp' => function ($sm) {
                    $config = $sm->getServiceLocator()->get('config');
                    return new PluploadHelp(
                        isset($config['QuConfig']['QuPlupload']) ? $config['QuConfig']['QuPlupload'] : array()
                    );
                },
                'PluploadHelpLoad' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $plupload_service = $sm->get('plupload_service');
                    $config = $sm->get('config');
                    return new PluploadHelpLoad($plupload_service,
                        isset($config['QuConfig']['QuPlupload']) ? $config['QuConfig']['QuPlupload'] : array()
                    );
                },
            ),
        );

    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
