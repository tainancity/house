<?php

App::uses('AppModel', 'Model');

class House extends AppModel {

    var $name = 'House';
    var $belongsTo = array(
        'Door' => array(
            'foreignKey' => 'door_id',
            'className' => 'Door',
        ),
        'Group' => array(
            'foreignKey' => 'group_id',
            'className' => 'Group',
        ),
        'Task' => array(
            'foreignKey' => 'task_id',
            'className' => 'Task',
        ),
    );
    var $hasMany = array(
        'HouseLog' => array(
            'foreignKey' => 'house_id',
            'dependent' => false,
            'className' => 'HouseLog',
        ),
    );

}
