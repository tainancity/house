<?php

App::uses('AppModel', 'Model');

class Place extends AppModel {

    var $name = 'Place';
    var $belongsTo = array(
        'Door' => array(
            'foreignKey' => 'foreign_id',
            'className' => 'Door',
            'conditions' => array(
                'Place.model' => 'Door',
            ),
        ),
        'Land' => array(
            'foreignKey' => 'foreign_id',
            'className' => 'Land',
            'conditions' => array(
                'Place.model' => 'Land',
            ),
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
        'PlaceLog' => array(
            'foreignKey' => 'place_id',
            'dependent' => false,
            'className' => 'PlaceLog',
        ),
    );

    public function beforeFind($query) {
        $loginMember = Configure::read('loginMember');
        if ($loginMember['group_id'] != 0 && $loginMember['group_id'] != 1) {
            $query['conditions']['Place.group_id'] = $loginMember['group_id'];
        }
        return $query;
    }

}
