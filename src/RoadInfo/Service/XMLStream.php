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
use GuzzleHttp;

class XMLStream implements DataSourceAwareInterface {
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  public function processWeatherStations(){
    $obj = $this->fetch(WEATHER_STATIONS);
    foreach($obj as $item){
      $this->processWeatherStationItem($item);
    }
  }

  public function processWeatherStationItem($item){
    //First we check to see if the weather station is available in the database
    $weatherStationService = new WeatherStation();
    $weatherStationService->setDataSource($this->pdo);
    $weatherStation = $weatherStationService->get($item->Nr);

    $data = array(
      "id" => $item->Nr,
      "segment_id" => $item->IdButur1,
      "segment2_id" => $item->IdButur2,
      "latitude" => $item->Breidd,
      "longitude" => -1 * $item->Lengd,
      "height" => ($item->Haed) ? $item->Haed : 0,
      "sea_height" => ($item->Sjavarhaed) ? $item->Sjavarhaed : 0,
      "name" => $item->Nafn,
      "dew_point" => ($item->Daggarmark) ? $item->Daggarmark : 0,
      "date" => strtotime($item->Dags),
      "airpressure" => ($item->Loftthrystingur) ? $item->Loftthrystingur : 0,
      "temperature" => ($item->Hiti) ? $item->Hiti : 0,
      "humidity" => ($item->Raki) ? $item->Raki : 0,
      "traffic_last_ten" => ($item->Umf10Min) ? $item->Umf10Min : 0,
      "traffic_accumulated" => ($item->UmfSum) ? $item->UmfSum : 0,
      "road_temperature" => ($item->Veghiti) ? $item->Veghiti : 0,
      "wind_direction" => ($item->Vindatt) ? $item->Vindatt : 0,
      "wind_direction_asc" => ($item->VindattAsc) ? $item->VindattAsc : 'N',
      "wind_direction_ast_dev" => ($item->VindattAstDev) ? $item->VindattAstDev : 0,
      "wind_speed" => ($item->Vindhradi) ? $item->Vindhradi : 0,
      "wind_gust" => ($item->Vindhvida) ? $item->Vindhvida : 0
    );

    if($weatherStation){
      $weatherStationService->update($weatherStation->id, $data);
    }
    else{
      $weatherStationService->create($data);
    }
  }

  public function processRoadConditions(){
    $obj = $this->fetch(ROAD_CONDITIONS);
    foreach( $obj as $item ){
      $this->processRoadConditionItem($item);
    }
  }

  /**
   * Fetches data from paths as a JSON object, with GuzzleHttp client, and returns it as a stdClass objects
   *
   * @param $path
   * @return mixed|object
   */
  public function fetch($path){
    $client = new GuzzleHttp\Client();
    $result = $client->get($path, [
      'headers' => [
        'Accept' => 'application/json'
      ]
    ]);

    $res = $result->getBody()->getContents();
    $res = json_decode($res);
    return $res;
  }

  /**
   * Process each Road condition item, in five steps
   * 1) Looks for the segment in the database and creates if it doesn't exist
   * 2) Looks for the condition type in the database and creates it if it doesn't exist
   * 3) Checks if the segment is a Route, and if it is, process it
   * 4) Checks if there is a road sign assigned to the segment, and if the road sign exists in the database.
   *    If not, it will be created
   * 5) Finally it stores the road condition in the database
   * @param $item
   */
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

      $routeSegmentService = new RouteSegment();
      $routeSegmentService->setDataSource($this->pdo);
      $routeSegment = $routeSegmentService->getRouteSegment($route, $segment);

      if(!$routeSegment){
        $data['route_id'] = $route;
        $data['segment_id'] = $segment;
        $routeSegmentService->create($data);
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

  /**
   * Reads the pattern JSON feed from Vegagerðin and processes it into the database, updating older info if exists
   * and creates a new entry for new additions.
   *
   * @throws \RoadInfo\Service\Exception
   */
  public function readPatterns(){
    $segmentPartService = new SegmentParts();
    $segmentPartService->setDataSource($this->pdo);

    $client = new GuzzleHttp\Client([
      'base_uri' => 'http://gagnaveita.vegagerdin.is'
    ]);
    $result = $client->get('/gis/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=gis:snjoleidir&srsName=EPSG:4326&maxFeatures=2000&outputFormat=application/json');
    $res = $result->getBody()->getContents();
    $res = json_decode($res);

    foreach($res->features as $feature){
      $data['object_id'] = $feature->properties->OBJECTID;
      $data['nr_vegur'] = $feature->properties->NRVEGUR;
      $data['nr_kafli'] = $feature->properties->NRKAFLI;
      $data['nafn'] = $feature->properties->NAFN;
      $data['id_butur'] = $feature->properties->IDBUTUR;

      /**
       * Generate three strings to store pattern configuration.  Low res points have three significant numbers,
       * mid res points have five and hi res have all.
       */
      $lowResPoints = "";
      $midResPoints = "";
      $hiResPoints = "";
      setlocale(LC_NUMERIC, 'en_US');
      foreach($feature->geometry->coordinates as $coord){
        $lowResPoints .= "[" . (float)round($coord[0], 3) . "," . (float)round($coord[1], 3) . "],";
        $midResPoints .= "[" . (float)round($coord[0], 5) . "," . (float)round($coord[1], 5) . "],";
        $hiResPoints .= "[" . $coord[0] . "," . $coord[1] . "],";
      }
      setlocale(LC_NUMERIC, 'is_IS');
      $data['low_res'] = $lowResPoints;
      $data['mid_res'] = $midResPoints;
      $data['hi_res'] = $hiResPoints;

      $centerOfPattern = (int)(sizeof($feature->geometry->coordinates) / 2);
      //We get the segment from the database, update the data and store it back
      $segmentService = new Segment();
      $segmentService->setDataSource($this->pdo);
      $segmentFromDatabase = $segmentService->get($data["id_butur"]);
      $segmentFromDatabase->center_lat = $feature->geometry->coordinates[$centerOfPattern][1];
      $segmentFromDatabase->center_lng = $feature->geometry->coordinates[$centerOfPattern][0];
      //$segmentService->update($segmentFromDatabase->id, (array)$segmentFromDatabase);

      $dataFromDatabase = $segmentPartService->getSegmentPart(
        $data['object_id'], $data['nr_vegur'], $data['nr_kafli'], $data['id_butur']
      );
      //Lastly we store the Segment Part in the database
      try{
        if($dataFromDatabase){
          $segmentPartService->update($data);
        }
        else{
          $segmentPartService->create($data);
        }
      }
      catch( PDOException $e){
        echo $e->getMessage();
        throw new Exception("Can't insert data for Segment Pattern");
      }
    }
  }

  public function setDataSource(\PDO $pdo) {
    $this->pdo = $pdo;
  }
}