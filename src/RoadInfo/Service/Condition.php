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
        SELECT * FROM Condition
        WHERE id = :id
      ");

      $statement->execute(array(
        'id' => $id
      ));
      $condition = $statement->fetchObject();

      if(!$condition){
        return false;
      }

      return $condition;
    }
    catch( PDOException $e ){
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
        SELECT * FROM Condition
        ORDER BY condition ASC
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
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