<?php

App::uses('AppController', 'Controller');

class HouseLogsController extends AppController {

    public $name = 'HouseLogs';
    public $paginate = array();
    public $helpers = array();

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'House' => 'House_id',
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            if (!empty($this->data)) {
                $this->data['HouseLog'][$foreignKeys[$foreignModel]] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (!empty($this->data)) {
            $this->HouseLog->create();
            if ($this->HouseLog->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);

        $belongsToModels = array(
            'listHouse' => array(
                'label' => '房屋',
                'modelName' => 'House',
                'foreignKey' => 'House_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            if ($foreignModel == $model['modelName']) {
                unset($belongsToModels[$key]);
                continue;
            }
            $this->set($key, $this->HouseLog->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

}
