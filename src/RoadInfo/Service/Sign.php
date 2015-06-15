<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 21/05/15
 * Time: 11:59
 */
namespace RoadInfo\Service;

use Mockery\CountValidator\Exception;
use PDOException;
use RoadInfo\Lib\DataSourceAwareInterface;


class Sign implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one Sign by id
   *
   * @param $id
   * @return bool|mixed
   */
  public function get($id){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM Sign
        WHERE id = :id
      ");

      $statement->execute(array(
        'id' => $id
      ));
      $sign = $statement->fetchObject();

      if(!$sign){
        return false;
      }

      return $sign;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Sign item [{$id}]", 0, $e);
    }
  }

  /**
   * Gets all Signs
   *
   * @return array
   */
  public function fetchAll(){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM Sign
        ORDER BY sign_url ASC
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
      throw new Exception("Can't get signs");
    }
  }

  public function getSignMarkers(){
    $return_arr = array();
    try{
      $signs = $this->fetchAll();

      foreach($signs as $sign){
        $statement = $this->pdo->prepare("
        SELECT rc.segment_id, rc.sign_id, s.center_lat, s.center_lng, si.`name`, si.sign_url
        FROM road_info.RoadCondition rc
        INNER JOIN Segment s
        ON rc.segment_id = s.id
        INNER JOIN Sign si
        ON si.id = rc.sign_id
        WHERE sign_id = :sign_id
        AND rc.forecast_date = (SELECT max(forecast_date) FROM RoadCondition)
      ");

        $statement->execute(array("sign_id" => $sign->id) );
        $key = ($sign->sign_url) ? $sign->sign_url : $sign->id;
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
   * Creates a Sign entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('Sign', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      throw new Exception("Can't create sign entry");
    }
  }

  /**
   * Updates a Sign entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update($id, array $data){
    try{
      $updateString = $this->updateString('Sign', $data, "id={$id}");
      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      throw new Exception("Can't update sign entry with id [{$id}]");
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