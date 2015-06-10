<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 26/05/15
 * Time: 11:32
 */
namespace RoadInfo\Service;

use RoadInfo\Lib\DataSourceAwareInterface;

class JSONStream implements DataSourceAwareInterface {
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  public function getRoadCondidtionsByCondition(){

  }

  public function setDataSource(\PDO $pdo) {
    $this->pdo = $pdo;
  }
}