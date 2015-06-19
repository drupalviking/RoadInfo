<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 12/06/15
 * Time: 13:06
 */
namespace RoadInfo\Service;

use Mockery\CountValidator\Exception;
use PDOException;
use RoadInfo\Lib\DataSourceAwareInterface;


class RouteSegment implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one Route by route id
   *
   * @param $routeId
   * @return bool|mixed
   */
  public function getRoute($routeId){
    try{
      $statement = $this->pdo->prepare("
        SELECT rs.route_id, rs.segment_id
        FROM road_info.RouteHasSegment rs
        WHERE route_id = :route_id
      ");

      $statement->execute(array(
        'route_id' => $routeId
      ));
      $segment = $statement->fetchAll();

      if(!$segment){
        return false;
      }

      return $segment;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Route [{$routeId}]", 0, $e);
    }
  }

  /**
   * Gets one record by route id and segment id
   *
   * @param $routeId
   * @param $segmentId
   * @return bool|mixed
   */
  public function getRouteSegment($routeId, $segmentId){
    try{
      $statement = $this->pdo->prepare("
        SELECT rs.route_id, rs.segment_id, r.short_name, r.long_name
        FROM road_info.RouteHasSegment rs
        INNER JOIN Route r ON r.id = rs.route_id
        WHERE route_id = :route_id
        AND segment_id = :segment_id
      ");

      $statement->execute(array(
        'route_id' => $routeId,
        'segment_id' => $segmentId
      ));
      $segment = $statement->fetchObject();

      if(!$segment){
        return false;
      }

      return $segment;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Route [{$routeId}]", 0, $e);
    }
  }

  /**
   * Gets all Routes
   *
   * @return array
   */
  public function fetchAll(){
    try{
      $statement = $this->pdo->prepare("
        SELECT rs.route_id, rs.segment_id, r.short_name, r.long_name
        FROM road_info.RouteHasSegment rs
        INNER JOIN Route r ON r.id = rs.route_id
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      throw new Exception("Can't get Weather stations");
    }
  }

  /**
   * Creates a Route has Segment entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('RouteHasSegment', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't create Route has Segment station entry");
    }
  }

  /**
   * Updates a Route has Segment station entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update($id, array $data){
    try{
      $updateString = $this->updateString('RouteHasSegment', $data, "id={$id}");
      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't update Route has Segment station entry with id [{$id}]");
    }
  }

  /**
   * Sets the Datasource
   *
   * @param \PDO $pdo
   * @return null
   */
  public function setDataSource(\PDO $pdo){
    $this->pdo = $pdo;
  }
}