<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 21/05/15
 * Time: 13:06
 */
namespace RoadInfo\Service;

define("ROAD_CONDITIONS", "http://gagnaveita.vegagerdin.is/api/faerd2014_1");
define("SEGMENT_CONDITIONS", "http://www4.vegagerdin.is/xml/faerd.xml");
define("WEATHER_STATIONS", "http://gagnaveita.vegagerdin.is/api/vedur2014_1");
define("WEB_CAMERAS", "http://gagnaveita.vegagerdin.is/api/vefmyndavelar2014_1");

use RoadInfo\Lib\DataSourceAwareInterface;

class XMLStream implements DataSourceAwareInterface {
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  public function processRoadConditions(){
    $obj = $this->fetchRoadConditions();
    echo "<h1>Hundur</h1>";
    foreach( $obj as $item ){
      $this->processRoadConditionItem($item);
    }
  }

  public function fetchRoadConditions(){
    $json = file_get_contents(ROAD_CONDITIONS);

    return json_decode($json);
  }

  public function processRoadConditionItem($item){
    //First we check to see if the segment is available in the database
    $segmentService = new Segment();
    $segmentService->setDataSource($this->pdo);
    $segment = $segmentService->get($item->IdButur);
    //If it isn't, then we create it
    if(!$segment){
      $data = array(
        "id" => $item->IdButur,
        "name_long" => $item->LangtNafn,
        "name_short" => $item->StuttNafn,
        "is_highland" => $item->ErHalendi,
      );
      $segment = $segmentService->create($data);
    }
    else{
      $segment = $segment->id;
    }

    //Next we check to see if the road condition is available in the database
    $conditionService = new Condition();
    $conditionService->setDataSource($this->pdo);
    $condition = $conditionService->get($item->IdAstand);
    //If it isn't, then we create it
    if(!$condition){
      $data = array(
        "id" => $item->IdAstand,
        "condition_long" => $item->FulltAstand,
        "condition_short" => $item->StuttAstand,
      );
      $condition = $conditionService->create($data);
    }
    else{
      $condition = $condition->id;
    }

    //Third condition to check is the Route
    if($item->IdLeid){
      $routeService = new Route();
      $routeService->setDataSource($this->pdo);
      $route = $routeService->getByShortName($item->IdLeid);
      if(!$route){
        $data = array(
          'short_name' => $item->IdLeid,
          'long_name' => $item->LeidNafn,
        );
        $route = $routeService->create($data);
      }
      else{
        $route = $route->id;
      }
    }
    else{
      $route = null;
    }

    //Last, we have to check for the sign
    if($item->Skilti){
      $signService = new Sign();
      $signService->setDataSource($this->pdo);
      $sign = $signService->get($item->Skilti);
      if(!$sign){
        $data = array(
          'id' => $item->Skilti,
          'name' => null
        );
        $sign = $signService->create($data);
      }
      else{
        $sign = $sign->id;
      }
    }
    else{
      $sign = null;
    }

    $roadConditionService = new RoadCondition();
    $roadConditionService->setDataSource($this->pdo);
    $data = array(
      'segment_id' => $segment,
      'forecast_date' => strtotime($item->DagsKeyrtUt),
      'comment' => $item->Aths,
      'road_condition_id' => $condition,
      'route_id' => ($route) ? $route : null,
      'sort_order' => $item->Rodun,
      'sign_id' => ($sign) ? $sign : null,
    );
    $roadConditionService->create($data);
  }

  public function setDataSource(\PDO $pdo) {
    $this->pdo = $pdo;
  }
}