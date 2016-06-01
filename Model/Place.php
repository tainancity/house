<?php

App::uses('AppModel', 'Model');

class Place extends AppModel {

    var $name = 'Place';
    var $belongsTo = array(
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
    var $hasAndBelongsToMany = array(
        'Door' => array(
            'joinTable' => 'place_links',
            'foreignKey' => 'place_id',
            'associationForeignKey' => 'foreign_id',
            'className' => 'Door',
            'conditions' => array(
                'PlaceLink.model' => 'Door',
            ),
        ),
        'Land' => array(
            'joinTable' => 'place_links',
            'foreignKey' => 'place_id',
            'associationForeignKey' => 'foreign_id',
            'className' => 'Land',
            'conditions' => array(
                'PlaceLink.model' => 'Land',
            ),
        ),
    );
    var $hasMany = array(
        'PlaceLog' => array(
            'foreignKey' => 'place_id',
            'dependent' => false,
            'className' => 'PlaceLog',
        ),
        'PlaceLink' => array(
            'foreignKey' => 'place_id',
            'dependent' => false,
            'className' => 'PlaceLink',
        ),
        'Tracker' => array(
            'foreignKey' => 'place_id',
            'dependent' => false,
            'className' => 'Tracker',
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
