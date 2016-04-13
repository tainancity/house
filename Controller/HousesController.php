<?php

App::uses('AppController', 'Controller');

class HousesController extends AppController {

    public $name = 'Houses';
    public $paginate = array();
    public $helpers = array('Olc');

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();

        $foreignKeys = array(
            'Door' => 'door_id',
            'Group' => 'group_id',
            'Task' => 'task_id',
        );

        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['House.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $foreignModel = '';
        }
        $foreignInfo = array();
        if (!empty($foreignModel)) {
            switch ($foreignModel) {
                case 'Task':
                    $record = $this->House->Task->find('first', array(
                        'conditions' => array('Task.id' => $foreignId),
                    ));
                    $foreignInfo = array(
                        'title' => $record['Task']['title'],
                        'description' => $record['Task']['description'],
                    );
                    break;
            }
        }
        $this->set('scope', $scope);
        $this->paginate['House']['limit'] = 20;
        $items = $this->paginate($this->House, $scope);
        foreach ($items AS $k => $item) {
            $items[$k]['House']['id'] = bin2hex($item['House']['id']);
        }
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
        $this->set('foreignInfo', $foreignInfo);
    }

    function admin_view($id = null) {
        if (!empty($id)) {
            $id = hex2bin($id);
        }
        if (!$id || !$this->data = $this->House->read(null, $id)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'Door' => 'door_id',
            'Group' => 'group_id',
            'Task' => 'task_id',
        );
        if (!array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $foreignModel = '';
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            if (!empty($foreignModel)) {
                $dataToSave['House'][$foreignKeys[$foreignModel]] = $foreignId;
            }

            $dataToSave['House']['id'] = $this->House->getNewUUID();
            $dataToSave['House']['group_id'] = $this->loginMember['group_id'];
            $dataToSave['House']['created_by'] = $dataToSave['House']['modified_by'] = $this->loginMember['id'];
            $this->House->create();
            if ($this->House->save($dataToSave)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            if ($this->House->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('id', $id);
        $this->data = $this->House->read(null, $id);

        $belongsToModels = array(
            'listDoor' => array(
                'label' => '門牌',
                'modelName' => 'Door',
                'foreignKey' => 'door_id',
            ),
            'listGroup' => array(
                'label' => '群組',
                'modelName' => 'Group',
                'foreignKey' => 'group_id',
            ),
            'listTask' => array(
                'label' => '專案任務',
                'modelName' => 'Task',
                'foreignKey' => 'task_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            $this->set($key, $this->House->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Please do following links in the page', true));
        } else if ($this->House->delete($id)) {
            $this->Session->setFlash(__('The data has been deleted', true));
        }
        $this->redirect(array('action' => 'index'));
    }

}
