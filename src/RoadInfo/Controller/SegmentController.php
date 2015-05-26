<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 21/05/15
 * Time: 16:01
 */
namespace RoadInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RoadInfo\Form\Segment;
use ArrayObject;

class SegmentController extends AbstractActionController{
  public function indexAction(){
    $sm = $this->getServiceLocator();
    $segmentService = $sm->get('RoadInfo\Service\Segment');

    if (($segment = $segmentService->get($this->params()->fromRoute('id', 0))) != false) {
      return new ViewModel(['segment' => $segment]);
    }
  }

  public function listAction(){
    $sm = $this->getServiceLocator();
    $segmentService = $sm->get('RoadInfo\Service\Segment');
    $segments = $segmentService->fetchAll();

    if( $segments ){
      return new ViewModel(["segments" => $segments]);
    }
    else{
      return $this->notFoundAction();
    }
  }

  public function updateAction() {
    $sm = $this->getServiceLocator();
    $segmentService = $sm->get('RoadInfo\Service\Segment');

    $form = new Segment();
    if (($segment = $segmentService->get($this->params()->fromRoute('id')) ) != false) {
      //POST
      //  post request
      if ($this->request->isPost()) {
        $form->setData($this->request->getPost());

        //VALID FORM
        //  form data is valid
        if ($form->isValid()) {
          $data = $form->getData();
          unset($data['submit']);
          $segmentService->update($segment->id, $data);
          return $this->redirect()
            ->toRoute('segment/list');
          //INVALID
          //  form data is invalid
        }
        else {
          $this->getResponse()->setStatusCode(400);
          return new ViewModel(
            [
              'segment' => $segment,
              'form' => $form,
            ]
          );
        }
        //QUERY
        //  get request
      }
      else {
        $form->bind(new ArrayObject((array) $segment));
        $view = new ViewModel(
          [
            'segment' => $segment,
            'form' => $form,
          ]
        );

        //$view->setTerminal($this->request->isXmlHttpRequest());
        return $view;
      }
    }
  }
}