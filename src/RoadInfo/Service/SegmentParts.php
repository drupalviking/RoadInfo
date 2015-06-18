<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 18/06/15
 * Time: 10:30
 */
namespace RoadInfo\Service;

use Mockery\CountValidator\Exception;
use PDOException;
use RoadInfo\Lib\DataSourceAwareInterface;


class SegmentParts implements DataSourceAwareInterface{
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * Gets one SegmentPart by objectId, nrVegur, nrKafli and idButur
   *
   * @param $objectId
   * @param $nrVegur
   * @param $nrKafli
   * @param $idButur
   * @return bool|mixed
   */
  public function getSegmentPart($objectId, $nrVegur, $nrKafli, $idButur){
    try{
      $statement = $this->pdo->prepare("
        SELECT *
        FROM road_info.SegmentParts sp
        WHERE object_id = :object_id
        AND nr_vegur = :nr_vegur
        AND nr_kafli = :nr_kafli
        AND id_butur = :id_butur
      ");

      $statement->execute(array(
        'object_id' => $objectId,
        'nr_vegur' => $nrVegur,
        'nr_kafli' => $nrKafli,
        'id_butur' => $idButur
      ));
      $segment = $statement->fetchObject();

      if(!$segment){
        return false;
      }

      return $segment;
    }
    catch( PDOException $e ){
      throw new Exception("Can't get Segment part", 0, $e);
    }
  }

  /**
   * Gets all SegmentParts, by SegmentPart Id
   *
   * @return array
   */
  public function fetchAllByPartId($partId){
    try{
      $statement = $this->pdo->prepare("
        SELECT *
        FROM road_info.SegmentParts sp
        WHERE id_butur = :id_butur
      ");

      $statement->execute();

      return $statement->fetchAll(array('id_butur' => $partId));
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      throw new Exception("Can't get Segment parts for {$partId}");
    }
  }

  /**
   * Creates a Segment Part entry in the database
   *
   * @param array $data
   * @return int
   */
  public function create(array $data){
    try{
      $insertString = $this->insertString('SegmentParts', $data);
      $statement = $this->pdo->prepare($insertString);
      $statement->execute($data);
      $id = (int)$this->pdo->lastInsertId();

      return $id;
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't create Segment part entry");
    }
  }

  /**
   * Updates a Segment part entry in the database
   *
   * @param $id
   * @param array $data
   * @return int
   */
  public function update(array $data){
    try{
      $updateString = $this->updateString(
        'SegmentParts',
        $data,
        "object_id={$data['object_id']}
          AND nr_vegur='{$data['nr_vegur']}'
          AND nr_kafli='{$data['nr_kafli']}'
          AND id_butur={$data['id_butur']}"
      );

      $statement = $this->pdo->prepare($updateString);
      $statement->execute($data);

      return $statement->rowCount();
    }
    catch( PDOException $e){
      echo "<pre>";
      print_r($e->getMessage());
      echo "</pre>";
      throw new Exception("Can't update Segment part entry");
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