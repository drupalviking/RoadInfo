<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RoadInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Model\FeedModel;


/**
 * Class IndexController.
 *
 * @package Stjornvisi\Controller
 */
class IndexController extends AbstractActionController
{
    /**
     * This is the landing page or the home page.
     *
     * It will display a <em>welcome</em> and a <em>sales pitch</em>
     * if the use is not logged in, else it will be the user's personal
     * profile.
     */
    public function indexAction()
    {
        //SERVICES
        $sm = $this->getServiceLocator();
        $segmentService = $sm->get('RoadInfo\Service\Segment');
        $signService = $sm->get('RoadInfo\Service\Sign');
        $routeService = $sm->get('RoadInfo\Service\Route');
        $conditionService = $sm->get('RoadInfo\Service\Condition');
        $xmlStreamService = $sm->get('RoadInfo\Service\XMLStream');

        $xmlStreamService->processRoadConditions();

        if (($segments = $segmentService->fetchAll()) != false) {
            return new ViewModel([
                'segments' => $conditionService->getRoadCondidtionsByCondition(),
                'signs' => $signService->getSignMarkers(),
                'routes' => $routeService->fetchAllWithSegmentData(),
            ]);
        }
    }
}
