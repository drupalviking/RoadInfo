<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 21/05/15
 * Time: 12:47
 */
namespace RoadInfo\Service;

use Mockery\CountValidator\Exception;
use PDOException;
use RoadInfo\Lib\DataSourceAwareInterface;


class RoadCondition implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one RoadCondition item by id
   *
   * @param $id
   * @return bool|mixed
   */
  public function get($id){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM RoadCondition
        WHERE id = :id
      ");

      $statement->execute(array(
        'id' => $id
      ));
      $roadcondition = $statement->fetchObject();

      if(!$roadcondition){
        return false;
      }

      return $roadcondition;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get RoadCondition item [{$id}]", 0, $e);
    }
  }

  public function getBySegmentAndForcastDate($segmentId, $forcastDate){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM RoadCondition
        WHERE segment_id = :segment_id
        AND forecast_date = :forecast_date
      ");

      $statement->execute(array(
        'segment_id' => $segmentId,
        'forecast_date' => $forcastDate
      ));
      $roadcondition = $statement->fetchObject();

      if(!$roadcondition){
        return false;
      }

      return $roadcondition;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get RoadCondition item [{$id}]", 0, $e);
    }
  }

  /**
   * Gets all newest conditions
   *
   * @return array
   */
  public function fetchAllNewest(){
    try{
      $roadConditions = $this->pdo->prepare("
        SELECT * FROM `RoadCondition` rc
        INNER JOIN Segment s ON s.id = rc.segment_id
        INNER JOIN `Condition` cond
        ON cond.id = rc.road_condition_id
        WHERE forecast_date = (SELECT max(forecast_date) FROM RoadCondition)
        ORDER BY segment_id ASC
      ");

      $roadConditions->execute();
      $roadConditions = $roadConditions->fetchAll();

      foreach($roadConditions as &$condition){
        $signs = $this->pdo->prepare("
        SELECT rc.segment_id, rc.sign_id, s.center_lat, s.center_lng, si.`name`, si.sign_url
        FROM road_info.RoadCondition rc
        INNER JOIN Segment s
        ON rc.segment_id = s.id
        INNER JOIN Sign si
        ON si.id = rc.sign_id
        WHERE segment_id = :segment_id
        AND rc.forecast_date = (SELECT max(forecast_date) FROM RoadCondition)
      ");

        $signs->execute(array("segment_id" => $condition->segment_id) );
        $condition->signs = $signs->fetchAll();

        $weatherStations = $this->pdo->prepare("
          SELECT * FROM WeatherStation
          WHERE segment_id = :segment_id
        ");

        $weatherStations->execute(array("segment_id" => $condition->segment_id) );
        $condition->weatherStations = $weatherStations->fetchAll();

        $segmentParts = $this->pdo->prepare("
          SELECT object_id, nr_vegur, nr_kafli, nafn, id_butur, mid_res as pattern
          FROM `road_info`.`SegmentParts`
          WHERE id_butur = :segment_id
        ");

        $segmentParts->execute(array("segment_id" => $condition->segment_id) );
        $condition->segmentParts = $segmentParts->fetchAll();
      }

      return $roadConditions;
    }
    catch( PDOException $e){
      echo $e->getMessage();
      throw new Exception("Can't get conditions");
    }
  }

  /**
   * Gets all Segments
   *
   * @return array
   */
  public function fetchAll(){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM RoadCondition rc
        ORDER BY rc.name_short ASC
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't get Road conditions");
    }
  }

  /**
   * Creates a Road Condition entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('RoadCondition', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't create road condition entry");
    }
  }

  /**
   * Updates a Road Condition entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update($id, array $data){
    try{
      $updateString = $this->updateString('RoadCondition', $data, "id={$id}");
      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      throw new Exception("Can't update road condition entry with id [{$id}]");
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