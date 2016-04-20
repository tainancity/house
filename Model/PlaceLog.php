<?php

App::uses('AppModel', 'Model');

class PlaceLog extends AppModel {

    var $name = 'PlaceLog';
    var $belongsTo = array(
        'Place' => array(
            'foreignKey' => 'place_id',
            'className' => 'Place',
        ),
        'Creator' => array(
            'foreignKey' => 'created_by',
            'className' => 'Member',
        ),
    );
    var $actsAs = array(
        'Media.Transfer',
        'Media.Coupler',
        'Media.Meta',
        'Media.Generator',
    );

}
