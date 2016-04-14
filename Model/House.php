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
        'Creator' => array(
            'foreignKey' => 'created_by',
            'className' => 'Member',
        ),
        'Modifier' => array(
            'foreignKey' => 'modified_by',
            'className' => 'Member',
        ),
    );
    var $hasMany = array(
        'HouseLog' => array(
            'foreignKey' => 'house_id',
            'dependent' => false,
            'className' => 'HouseLog',
        ),
    );

    public function beforeFind($query) {
        $loginMember = Configure::read('loginMember');
        if ($loginMember['group_id'] != 1) {
            $query['conditions']['House.group_id'] = $loginMember['group_id'];
        }
        return $query;
    }

}
