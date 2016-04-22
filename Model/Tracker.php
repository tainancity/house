<?php

class Tracker extends AppModel {

    public $name = 'Tracker';
    var $belongsTo = array(
        'Project' => array(
            'foreignKey' => 'project_id',
            'className' => 'Project',
        ),
        'Place' => array(
            'foreignKey' => 'place_id',
            'className' => 'Place',
        ),
        'Group' => array(
            'foreignKey' => 'group_id',
            'className' => 'Group',
        ),
        'Creator' => array(
            'foreignKey' => 'created_by',
            'className' => 'Member',
        ),
        'Completer' => array(
            'foreignKey' => 'completed_by',
            'className' => 'Member',
        ),
    );

}
