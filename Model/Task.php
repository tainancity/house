<?php

App::uses('AppModel', 'Model');

class Task extends AppModel {

    var $name = 'Task';
    var $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required',
            ),
        ),
    );
    var $actsAs = array(
    );
    var $hasAndBelongsToMany = array(
        'Group' => array(
            'joinTable' => 'groups_tasks',
            'foreignKey' => 'Task_id',
            'associationForeignKey' => 'Group_id',
            'className' => 'Group',
        ),
    );
    var $hasMany = array(
        'House' => array(
            'foreignKey' => 'Task_id',
            'dependent' => false,
            'className' => 'House',
        ),
    );

    function afterSave($created, $options = array()) {
        
    }

}
