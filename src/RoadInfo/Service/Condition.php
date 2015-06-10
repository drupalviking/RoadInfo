<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 21/05/15
 * Time: 11:17
 */
namespace RoadInfo\Service;

use Mockery\CountValidator\Exception;
use PDOException;
use RoadInfo\Lib\DataSourceAwareInterface;


class Condition implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one condition by id
   *
   * @param $id
   * @return bool|mixed
   */
  public function get($id){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM `Condition`
        WHERE id = :id
      ");

      $statement->execute(array(
        'id' => (int)$id
      ));

      $condition = $statement->fetchObject();

      if(!$condition){
        return false;
      }

      return $condition;
    }
    catch( PDOException $e ){
      //echo "<pre>";
      //print_r($e->getMessage());
      throw new Exception("Can't get Condition item [{$id}]", 0, $e);
    }
  }

  /**
   * Gets all conditions
   *
   * @return array
   */
  public function fetchAll(){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM `Condition`
        ORDER BY condition_short ASC
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
      echo $e->getMessage();
      throw new Exception("Can't get conditions");
    }
  }

  /**
   * Creates a Condition entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('Condition', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      throw new Exception("Can't create condition entry");
    }
  }

  /**
   * Updates a Condition entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update($id, array $data){
    try{
      $updateString = $this->updateString('Condition', $data, "id={$id}");
      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      throw new Exception("Can't update condition entry with id [{$id}]");
    }
  }

  public function getRoadCondidtionsByCondition(){
    $return_arr = array();
    try{
      $conditions = $this->fetchAll();

      foreach($conditions as $condition){
        $statement = $this->pdo->prepare("
        SELECT rc.id, rc.segment_id, rc.forecast_date, rc.line_color, s.name_long, s.name_short, sp.pattern
        FROM road_info.RoadCondition rc
        INNER JOIN Segment s
        ON s.id = rc.segment_id
        INNER JOIN SegmentParts sp
        ON sp.id_butur = rc.segment_id
        WHERE rc.road_condition_id = :id
        AND forecast_date = (SELECT max(forecast_date) FROM RoadCondition)
        ORDER BY road_condition_id;
      ");

        $statement->execute(array("id" => $condition->id) );
        $key = ($condition->condition_url) ? $condition->condition_url : $condition->id;
        $return_arr[$key] = $statement->fetchAll();
      }

      return $return_arr;

    }
    catch( PDOException $e){
      echo $e->getMessage();
      throw new Exception("Can't get conditions");
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