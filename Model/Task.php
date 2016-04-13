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
    var $hasAndBelongsToMany = array(
        'Group' => array(
            'joinTable' => 'groups_tasks',
            'foreignKey' => 'task_id',
            'associationForeignKey' => 'group_id',
            'className' => 'Group',
        ),
    );
    var $hasMany = array(
        'House' => array(
            'foreignKey' => 'task_id',
            'dependent' => false,
            'className' => 'House',
        ),
    );

}
