<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

/*

CREATE TABLE IF NOT EXISTS `qu-plupload` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tmp_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `error` int(255) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

*/

return array(

    'controllers' => array(
        'invokables' => array(
            'QuPlupload' => 'QuPlupload\Controller\PluploadController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'quplupload' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/quplupload',
                    'defaults' => array(
                        'controller' => 'QuPlupload',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'upload' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/upload[/:id]',
                            'constraints' => array(
                                'action'     => 'upload',
                                'id'        => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'QuPlupload',
                                'action'     => 'upload',
                            ),
                        ),
                    ),
                    'dump' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/load[/:id]',
                            'constraints' => array(
                                'action'     => 'load',
                                'id'        => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'QuPlupload',
                                'action'     => 'load',
                            ),
                        ),
                    ),
                    'remove' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/remove[/:id][/:id_parent]',
                            'constraints' => array(
                                'action'     => 'remove',
                                'id'         => '[0-9]+',
                                'id_parent'  => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'QuPlupload',
                                'action'     => 'remove',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'aliases' => array(
            'plupload_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'QuConfig'=>array(

        // Config Plupload
        'QuPlupload'=>array(

            'tableName'          => 'qu-plupload',
            'UrlUpload'          => '/quplupload/upload',
            'UrlRemove'          => '/quplupload/remove',
            'UrlLoad'            => '/quplupload/load',
            'DirUpload'          => '/uploads/files/plupload',
            'DirUploadAbsolute'  => dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/files/plupload',
            'DirJs'              => 'js/plugins/plupload',
            'Resize'             => array('900','1000'), //$width, $height
            'ThumbResize'        => array(

                /* Properties  GdThumb Class Definition

                public function resize ($maxWidth = 0, $maxHeight = 0)
                public function adaptiveResize ($width, $height)
                public function resizePercent ($percent = 0)
                public function cropFromCenter ($cropWidth, $cropHeight = null)
                public function crop ($startX, $startY, $cropWidth, $cropHeight)

                */

                'xl' => array('resize','700','800'),
                'l'  => array('resize','600','550'),
                'm'  => array('resize','500','418'),
                's'  => array('adaptiveResize','30','20'),

            ),
            /**
             * @package unfinished
             * @todo finish config parameters Plupload
             */
        ),
    ),

);