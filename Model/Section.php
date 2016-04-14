<?php

class Section extends AppModel {

    public $name = 'Section';
    var $hasMany = array(
        'Land' => array(
            'foreignKey' => 'section_id',
            'className' => 'Land',
        ),
    );

}
