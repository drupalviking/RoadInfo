<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 02/07/15
 * Time: 08:28
 */
namespace RoadInfo\Service;

use RoadInfo\Lib\DataSourceAwareInterface;

class JavascriptService implements DataSourceAwareInterface {
  use DatabaseService;

  /**
   * @var \PDO
   */
  private $pdo;

  public function generateJavascript(){
    $javascript = $this->generateString();
    //$javascriptFile = fopen("");
  }

  private function generateString(){
    $string = "";
    $string .= "\n
    var map = L.map('map',\n
    {\n
      center: [65.10418, -19.0],\n
      zoom: 7,\n
      maxZoom: 11,\n
      minZoom: 7\n
    });\n";
    $string .= "\n
    var mainLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);\n
    var watercolorLayer = new L.StamenTileLayer('watercolor');\n
    ";


  }

  public function setDataSource(\PDO $pdo) {
    $this->pdo = $pdo;
  }
}