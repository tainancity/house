<?php

App::uses('AppModel', 'Model');

class HouseLog extends AppModel {

    var $name = 'HouseLog';
    var $belongsTo = array(
        'House' => array(
            'foreignKey' => 'house_id',
            'className' => 'House',
        ),
        'Creator' => array(
            'foreignKey' => 'created_by',
            'className' => 'Member',
        ),
    );

}
