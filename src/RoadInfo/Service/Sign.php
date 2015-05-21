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
        ORDER BY short_name ASC
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
      throw new Exception("Can't get signs");
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