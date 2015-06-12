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


class WeatherStation implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one Weather Station by id
   *
   * @param $id
   * @return bool|mixed
   */
  public function get($id){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM `WeatherStation`
        WHERE id = :id
      ");

      $statement->execute(array(
        'id' => $id
      ));
      $segment = $statement->fetchObject();

      if(!$segment){
        return false;
      }

      return $segment;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Weather station item [{$id}]", 0, $e);
    }
  }

  /**
   * Gets all Weather stations
   *
   * @return array
   */
  public function fetchAll(){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM `WeatherStation`
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
   * Creates a Weather station entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('WeatherStation', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't create Weather station entry");
    }
  }

  /**
   * Updates a Weather station entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update($id, array $data){
    try{
      $updateString = $this->updateString('WeatherStation', $data, "id={$id}");
      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't update Weather station entry with id [{$id}]");
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