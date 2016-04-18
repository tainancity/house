<?php

App::uses('AppController', 'Controller');

class PlaceLogsController extends AppController {

    public $name = 'PlaceLogs';
    public $paginate = array();
    public $helpers = array();

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'Place' => 'Place_id',
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            if (!empty($this->data)) {
                $this->data['PlaceLog'][$foreignKeys[$foreignModel]] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (!empty($this->data)) {
            $this->PlaceLog->create();
            if ($this->PlaceLog->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);

        $belongsToModels = array(
            'listPlace' => array(
                'label' => '房屋',
                'modelName' => 'Place',
                'foreignKey' => 'Place_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            if ($foreignModel == $model['modelName']) {
                unset($belongsToModels[$key]);
                continue;
            }
            $this->set($key, $this->PlaceLog->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

}
