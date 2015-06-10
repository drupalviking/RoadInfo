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
define("SHAPE_GENERATOR", "http://www2.turistforeningen.no/routing.php?url=http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson");

use RoadInfo\Lib\DataSourceAwareInterface;

class XMLStream implements DataSourceAwareInterface {
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  public function processRoadConditions(){
    $obj = $this->fetchRoadConditions();
    echo "<h1>Process Road Conditions</h1>";
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

    $dataFromDatabase = $roadConditionService->getBySegmentAndForcastDate($segment, strtotime($item->DagsKeyrtUt));

    if(!$dataFromDatabase){
      $data = array(
        'segment_id' => $segment,
        'forecast_date' => strtotime($item->DagsKeyrtUt),
        'comment' => $item->Aths,
        'road_condition_id' => $condition,
        'route_id' => ($route) ? $route : null,
        'sort_order' => $item->Rodun,
        'sign_id' => ($sign) ? $sign : null,
        'line_color' => (isset($item->Linulitur)) ? $item->Linulitur : "#000000"
      );
      $roadConditionService->create($data);
    }
  }

  /**
   * Checks to see if we have a shape for certain road segment.  If not, it sends an API request
   * to the Norveigian Web service and stores the results in the database
   *
   * @return true
   */
  public function processShapes(){
    //&v=foot&fast=1&layer=mapnik&flat=61.494951802903145&flon=8.810966491699235&tlat=61.486414253053795&tlon=8.830604553222656
    $segmentService = new Segment();
    $segmentService->setDataSource($this->pdo);
    $segments = $segmentService->fetchAll();
    foreach($segments as $segment){
      if(isset($segment->start_lat)){
        if(!isset($segment->path_data)){
          $string = SHAPE_GENERATOR . "&v=foot&fast=1&layer=mapnik&flat=" . $segment->start_lat .
            "&flon=" . $segment->start_lng .
            "&tlat=" . $segment->end_lat .
            "&tlon=" . $segment->end_lng;
            $json = file_get_contents($string);
            $segment->path_data = $json;

            $segmentService->update($segment->id, (array)$segment);
        }
      }
    }

    return true;
  }

  public function readPatternFile(){
    $file = simplexml_load_file('/Users/drupalviking/Desktop/snjoleidir.kml');
    $data = array();
    foreach($file->Document->Folder->Placemark as $pattern){
      $object = json_decode(json_encode($pattern));
      $data['object_id'] = $object->ExtendedData->SchemaData->SimpleData[0];
      $data['nr_vegur'] = $object->ExtendedData->SchemaData->SimpleData[1];
      $data['nr_kafli'] = $object->ExtendedData->SchemaData->SimpleData[2];
      $data['nafn'] = $object->ExtendedData->SchemaData->SimpleData[3];
      $data['id_butur'] = $object->ExtendedData->SchemaData->SimpleData[4];
      $lineString = $object->LineString->coordinates;

      //Take the line string and split it up into an array, one part per array item
      $lineArray = explode(' ', $lineString);

      //Then we need to take each array item, and split that up again, into two to three
      //pieces (the last piece might be an height attribute)
      $pattern = "";
      foreach($lineArray as $item) {
        $line = explode(",", $item);
        $pattern .= "[" . $line[0] . "," . $line[1] . "],";
      }
      $data['pattern'] = $pattern;

      //We also need to find the middle point, in order to place a sign on the road,
      //if there is one.
      $centerOfPattern = (int)(sizeof($lineArray) / 2);
      $centerPoint = explode(",", $lineArray[$centerOfPattern]);

      //We get the segment from the database, update the data and store it back
      $segmentService = new Segment();
      $segmentService->setDataSource($this->pdo);
      $segmentFromDatabase = $segmentService->get($data["id_butur"]);
      $segmentFromDatabase->center_lat = $centerPoint[1];
      $segmentFromDatabase->center_lng = $centerPoint[0];
      $segmentService->update($segmentFromDatabase->id, (array)$segmentFromDatabase);

      //Lastly we store the Segment Part in the database
      try{
        //$insertString = $this->insertString('SegmentParts', $data);
        //$statement = $this->pdo->prepare($insertString);
        //$statement->execute($data);
      }
      catch( PDOException $e){
        echo $e->getMessage();
        throw new Exception("Can't get conditions");
      }
    }
  }

  public function setDataSource(\PDO $pdo) {
    $this->pdo = $pdo;
  }
}