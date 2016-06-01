<?php

App::uses('AppModel', 'Model');

class PlaceLink extends AppModel {

    var $name = 'PlaceLink';
    var $belongsTo = array(
        'Place' => array(
            'foreignKey' => 'place_id',
            'className' => 'Place',
        ),
        'Door' => array(
            'foreignKey' => 'foreign_id',
            'className' => 'Door',
            'conditions' => array(
                'PlaceLink.model' => 'Door',
            ),
        ),
        'Land' => array(
            'foreignKey' => 'foreign_id',
            'className' => 'Land',
            'conditions' => array(
                'PlaceLink.model' => 'Land',
            ),
        ),
    );

}
