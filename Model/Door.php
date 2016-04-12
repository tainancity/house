<?php

App::uses('AppModel', 'Model');

class Door extends AppModel {

    var $name = 'Door';

    var $validate = array(

        'floor' => array(

            'numberFormat' => array(

                'rule' => 'numeric',

                'message' => 'Wrong format',

                'allowEmpty' => true,

            ),

        ),

    );
                

    var $actsAs = array(

    );





    function afterSave($created, $options = array()) {

	}

}