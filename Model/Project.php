<?php

class Project extends AppModel {

    public $name = 'Project';
    var $belongsTo = array(
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
        'Tracker' => array(
            'foreignKey' => 'project_id',
            'className' => 'Land',
        ),
    );

}
