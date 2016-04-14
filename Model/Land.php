<?php

class Land extends AppModel {

    public $name = 'Land';
    var $belongsTo = array(
        'Section' => array(
            'foreignKey' => 'section_id',
            'className' => 'Section',
        ),
    );

}
