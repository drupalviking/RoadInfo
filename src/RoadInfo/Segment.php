<?php
/**
 * Created by PhpStorm.
 * User: drupalviking
 * Date: 06/05/15
 * Time: 13:27
 */
namespace RoadInfo\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class Segment extends Form{

  public function __construct($name = null)
  {
    parent::__construct( strtolower( str_replace('\\','-',get_class($this) ) ));

    $this->setAttribute('method', 'post');

    $this->add(array(
      'name' => 'start_lat',
      'type' => 'Zend\Form\Element\Text',
      'attributes' => array(
        'placeholder' => 'Breiddargráða (Norðlægrar breiddar)',
        'required' => 'required',
        'tabindex' => 3
      ),
      'options' => array(
        'label' => 'Breiddargráða byrjunarpunkts',
      ),
    ));

    $this->add(array(
      'name' => 'start_lng',
      'type' => 'Zend\Form\Element\Text',
      'attributes' => array(
        'placeholder' => 'Lengdargráða (Vestlægrar lengdar (neikvæð tala))',
        'required' => 'required',
        'tabindex' => 4
      ),
      'options' => array(
        'label' => 'Lengdargráða byrjunarpunkts',
      ),
    ));

    $this->add(array(
      'name' => 'end_lat',
      'type' => 'Zend\Form\Element\Text',
      'attributes' => array(
        'placeholder' => 'Breiddargráða (Norðlægrar breiddar)',
        'required' => 'required',
        'tabindex' => 3
      ),
      'options' => array(
        'label' => 'Breiddargráða endapunkts',
      ),
    ));

    $this->add(array(
      'name' => 'end_lng',
      'type' => 'Zend\Form\Element\Text',
      'attributes' => array(
        'placeholder' => 'Lengdargráða (Vestlægrar lengdar (neikvæð tala))',
        'required' => 'required',
        'tabindex' => 4
      ),
      'options' => array(
        'label' => 'Lengdargráða endapunkts',
      ),
    ));

    $this->add(array(
      'name' => 'center_lat',
      'type' => 'Zend\Form\Element\Text',
      'attributes' => array(
        'placeholder' => 'Breiddargráða (Norðlægrar breiddar)',
        'required' => 'required',
        'tabindex' => 3
      ),
      'options' => array(
        'label' => 'Breiddargráða miðjupunkts (fyrir skilti)',
      ),
    ));

    $this->add(array(
      'name' => 'center_lng',
      'type' => 'Zend\Form\Element\Text',
      'attributes' => array(
        'placeholder' => 'Lengdargráða (Vestlægrar lengdar (neikvæð tala))',
        'required' => 'required',
        'tabindex' => 4
      ),
      'options' => array(
        'label' => 'Lengdargráða miðjupunkts (fyrir skilti)',
      ),
    ));

    $this->add(array(
      'name' => 'submit',
      'type' => 'Zend\Form\Element\Submit',
      'attributes' => array(
        //'value' => 'Submit',
      ),
      'options' => array(
        'label' => 'Submit',
      ),
    ));
  }
}
