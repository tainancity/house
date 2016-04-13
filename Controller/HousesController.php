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
            $this->redirect(array('controller' => 'tasks'));
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
        $this->paginate['House']['contain'] = array(
            'Modifier' => array(
                'fields' => array('username'),
            ),
        );
        $items = $this->paginate($this->House, $scope);
        foreach ($items AS $k => $item) {
            $items[$k]['House']['id'] = bin2hex($item['House']['id']);
        }
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
        $this->set('foreignInfo', $foreignInfo);
        $this->set('groups', $this->House->Group->find('list'));
        $this->set('tasks', $this->House->Task->find('list'));
    }

    function admin_view($id = null) {
        if (!empty($id)) {
            $id = hex2bin($id);
            $item = $this->House->find('first', array(
                'conditions' => array('House.id' => $id),
                'contain' => array(
                    'Group' => array(
                        'fields' => array('name'),
                    ),
                    'Task' => array(
                        'fields' => array('title'),
                    ),
                    'Creator' => array(
                        'fields' => array('username'),
                    ),
                    'Modifier' => array(
                        'fields' => array('username'),
                    ),
                    'HouseLog' => array(
                        'order' => array('HouseLog.created' => 'DESC'),
                        'Creator' => array(
                            'fields' => array('username'),
                        ),
                    ),
                ),
            ));
            $item['House']['id'] = bin2hex($item['House']['id']);
        }
        if (!empty($item)) {
            $this->set('item', $item);
        } else {
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
        if (empty($foreignModel)) {
            $this->redirect(array('controller' => 'tasks'));
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
                $this->House->HouseLog->create();
                $this->House->HouseLog->save(array('HouseLog' => array(
                        'id' => $this->House->getNewUUID(),
                        'house_id' => $dataToSave['House']['id'],
                        'status' => $dataToSave['House']['status'],
                        'date_visited' => $dataToSave['HouseLog']['date_visited'],
                        'created_by' => $this->loginMember['id'],
                        'note' => $dataToSave['HouseLog']['note'],
                )));
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'view', bin2hex($dataToSave['House']['id'])));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_edit($id = null) {
        if (!empty($id)) {
            $id = hex2bin($id);
        }
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            $this->House->id = $id;
            $dataToSave['House']['modified_by'] = $this->loginMember['id'];
            if ($this->House->save($dataToSave)) {
                $this->House->HouseLog->create();
                $this->House->HouseLog->save(array('HouseLog' => array(
                        'id' => $this->House->getNewUUID(),
                        'house_id' => $id,
                        'status' => $dataToSave['House']['status'],
                        'date_visited' => $dataToSave['HouseLog']['date_visited'],
                        'created_by' => $this->loginMember['id'],
                        'note' => $dataToSave['HouseLog']['note'],
                )));
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'view', bin2hex($id)));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('id', $id);
        $this->data = $this->House->read(null, $id);
    }

    function admin_delete($id = null) {
        if (!empty($id)) {
            $id = hex2bin($id);
        }
        if (!$id) {
            $this->Session->setFlash(__('Please do following links in the page', true));
        } else if ($this->House->delete($id)) {
            $this->Session->setFlash(__('The data has been deleted', true));
        }
        $this->redirect(array('action' => 'index'));
    }

}
