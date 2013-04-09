<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PluploadController extends AbstractActionController
{

    /**
     * @return array|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $view          = new ViewModel();
        $view->id      = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $view->model   = $this->getEvent()->getRouteMatch()->getParam('model', 'model');
        $this->Config  = $this->getServiceLocator()->get('Config');
        $view->DirJs   = $this->Config['QuConfig']['QuPlupload']['DirJs'];

        return $view;
    }

    /**
     * @return array
     */
    public function loadAction()
    {
        $view        = new ViewModel();
        $view->id    = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $view->model = $this->getEvent()->getRouteMatch()->getParam('model', 'model');
        return $view->setTerminal(true);
    }

    /**
     * @return mixed
     */
    public function uploadAction()
    {
        if ($this->getRequest()->isPost()) {

            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $id    = $this->getEvent()->getRouteMatch()->getParam('id', 0);
            $model = $this->getEvent()->getRouteMatch()->getParam('model', 'model');
            $PluploadService = $this->getServiceLocator()->get('plupload_service');

            $PluploadService->uploadPlupload($id,$data,$model);

        }

        $view = new ViewModel();
        return $view->setTerminal(true);

    }

    /**
     * @return mixed
     */
    public function removeAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $PluploadService = $this->getServiceLocator()->get('plupload_service');
        $PluploadService->PluploadRemove($id);

        //ReloadLoad List
        $view        = new ViewModel();
        $view->id    = $this->getEvent()->getRouteMatch()->getParam('id_parent', 0);
        $view->model = $this->getEvent()->getRouteMatch()->getParam('model', 'model');
        return $view->setTerminal(true);
    }
}