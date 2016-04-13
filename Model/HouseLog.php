<?php

App::uses('AppModel', 'Model');

class HouseLog extends AppModel {

    var $name = 'HouseLog';
    var $actsAs = array(
    );
    var $belongsTo = array(
        'House' => array(
            'foreignKey' => 'House_id',
            'className' => 'House',
        ),
    );

    function afterSave($created, $options = array()) {
        
    }

}
