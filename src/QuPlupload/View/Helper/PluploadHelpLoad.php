<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\View\Helper;

use Zend\View\Helper\AbstractHelper;
use QuPlupload\Util;

class PluploadHelpLoad extends AbstractHelper
{

    /**
     * @var
     */
    protected $pluploadService;

    /**
     * @var
     */
    protected $Config;

    /**
     * @param $pluploadService
     * @param $Config
     */
    public function __construct($pluploadService,$Config)
    {
        $this->pluploadService = $pluploadService;
        $this->Config          = $Config;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function __invoke($id = 0)
    {
        $this->pluploadService->setPluploadIdList($id);
        $listDb = $this->pluploadService->getPluploadList();
        $Util   = new Util();

        if(count($listDb) > 0){

            $list = '<ul>';

            foreach($listDb as $a){

                $type      = explode('/',$a->getType());
                $url       = $this->Config['DirUpload'] . '/' . $a->getName();
                $urlSmall  = $this->Config['DirUpload'] . '/s' . $a->getName();
                $name      = $a->getName();
                $id_in     = $a->getIdPlupload();
                $size      = $Util->formatBytes($a->getSize());

                if(file_exists($this->Config['DirUploadAbsolute'].'/'.$a->getName())){

                    if($type[0] ==  'image'){

                        $list .= '
                        <li>
                            <a href="'.$url.'" class="img">
                                <img src="'.$urlSmall.'">
                                <div class="name">'.$name.'</div>
                                <span class="label size">'.$size.'</span>
                                <div id="'.$id_in.'" class="action"><a href="#"></a></div>
                            </a>
                        </li>';

                    }else{

                        $list .= '
                        <li>
                            <a href="'.$url.'" class="doc">
                                <span class="iconb" data-icon="&#xe013;"></span>
                                <div class="name">'.$name.'</div>
                                 <span class="label size">'.$size.'</span>
                                <div id="'.$id_in.'" class="action"><a href="#"></a></div>
                            </a>
                        </li>';
                    }

                }
            }

            $list .= '</ul>';

            $list .= "
            <script type=\"text/javascript\">

                $('.action').click(function(){
                    $('.PluploadLoad').load('". $this->Config['UrlRemove'] ."/' + $(this).attr('id') + '/". $id ."');
                });

            </script>";

            return $list;

        }else{

            return false;
        }

    }
}