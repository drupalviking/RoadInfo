<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 21/05/15
 * Time: 11:54
 */
namespace RoadInfo\Service;

use Mockery\CountValidator\Exception;
use PDOException;
use RoadInfo\Lib\DataSourceAwareInterface;


class Route implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one route by id
   *
   * @param $id
   * @return bool|mixed
   */
  public function get($id){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM Route
        WHERE id = :id
      ");

      $statement->execute(array(
        'id' => $id
      ));
      $route = $statement->fetchObject();

      if(!$route){
        return false;
      }

      return $route;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Route item [{$id}]", 0, $e);
    }
  }

  /**
   * Gets one route by id
   *
   * @param $id
   * @return bool|mixed
   */
  public function getByShortName($short_name){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM Route
        WHERE short_name = :short_name
      ");

      $statement->execute(array(
        'short_name' => $short_name
      ));
      $route = $statement->fetchObject();

      if(!$route){
        return false;
      }

      return $route;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Route item [{$short_name}]", 0, $e);
    }
  }

  /**
   * Gets all routes
   *
   * @return array
   */
  public function fetchAll(){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM Route
        ORDER BY short_name ASC
      ");

      $statement->execute();

      return $statement->fetchAll();
    }
    catch( PDOException $e){
      throw new Exception("Can't get conditions");
    }
  }

  public function fetchAllWithSegmentData(){
    try{
      $statement = $this->pdo->prepare("
        SELECT * FROM Route
        ORDER BY short_name ASC
      ");

      $statement->execute();

      $routes = $statement->fetchAll();

      foreach($routes as &$route) {
        $statement = $this->pdo->prepare("
          SELECT segment_id
          FROM road_info.RouteHasSegment rc
          WHERE route_id = :route_id
        ");

        $statement->execute(array("route_id" => $route->id));
        $route->segments = $statement->fetchAll();
      }

      return $routes;
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't get conditions");
    }
  }

  /**
   * Creates a Route entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('Route', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      throw new Exception("Can't create route entry");
    }
  }

  /**
   * Updates a Route entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update($id, array $data){
    try{
      $updateString = $this->updateString('Route', $data, "id={$id}");
      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      throw new Exception("Can't update route entry with id [{$id}]");
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