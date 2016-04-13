<?php

App::uses('AppModel', 'Model');

class House extends AppModel {

    var $name = 'House';
    var $actsAs = array(
    );
    var $belongsTo = array(
        'Door' => array(
            'foreignKey' => 'Door_id',
            'className' => 'Door',
        ),
        'Group' => array(
            'foreignKey' => 'Group_id',
            'className' => 'Group',
        ),
        'Task' => array(
            'foreignKey' => 'Task_id',
            'className' => 'Task',
        ),
    );
    var $hasMany = array(
        'HouseLog' => array(
            'foreignKey' => 'House_id',
            'dependent' => false,
            'className' => 'HouseLog',
        ),
    );

    function afterSave($created, $options = array()) {
        
    }

}
