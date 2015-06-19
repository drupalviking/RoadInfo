<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 11/05/15
 * Time: 09:38
 */
namespace RoadInfo\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class ConsoleController extends AbstractActionController{
  public function processRoadConditionStreamAction(){
    $sm = $this->getServiceLocator();
    $xmlStreamService = $sm->get('RoadInfo\Service\XMLStream');
    $xmlStreamService->processRoadConditions();
  }

  public function processShapes(){
    $sm = $this->getServiceLocator();
    $xmlStreamService = $sm->get('RoadInfo\Service\XMLStream');
    $xmlStreamService->readPatterns();
  }

  public function processWeatherStations(){
    $sm = $this->getServiceLocator();
    $xmlStreamService = $sm->get('RoadInfo\Service\XMLStream');
    $xmlStreamService->processWeatherStations();
  }
}
