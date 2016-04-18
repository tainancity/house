<?php

App::uses('AppController', 'Controller');

class PlacesController extends AppController {

    public $name = 'Places';
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
            $scope['Place.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $this->redirect(array('controller' => 'tasks'));
            $foreignModel = '';
        }
        $foreignInfo = array();
        if (!empty($foreignModel)) {
            switch ($foreignModel) {
                case 'Task':
                    $record = $this->Place->Task->find('first', array(
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
        $this->paginate['Place']['limit'] = 20;
        $this->paginate['Place']['contain'] = array(
            'Modifier' => array(
                'fields' => array('username'),
            ),
        );
        $items = $this->paginate($this->Place, $scope);
        foreach ($items AS $k => $item) {
            $items[$k]['Place']['id'] = bin2hex($item['Place']['id']);
        }
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
        $this->set('foreignInfo', $foreignInfo);
        $this->set('groups', $this->Place->Group->find('list'));
        $this->set('tasks', $this->Place->Task->find('list'));
    }

    function admin_view($id = null) {
        if (!empty($id)) {
            $id = hex2bin($id);
            $item = $this->Place->find('first', array(
                'conditions' => array('Place.id' => $id),
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
                    'PlaceLog' => array(
                        'order' => array('PlaceLog.created' => 'DESC'),
                        'Creator' => array(
                            'fields' => array('username'),
                        ),
                    ),
                ),
            ));
            $item['Place']['id'] = bin2hex($item['Place']['id']);
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
                $dataToSave['Place'][$foreignKeys[$foreignModel]] = $foreignId;
            }

            $dataToSave['Place']['id'] = $this->Place->getNewUUID();
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }

            $dataToSave['Place']['created_by'] = $dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            $this->Place->create();
            if ($this->Place->save($dataToSave)) {
                $this->Place->PlaceLog->create();
                $this->Place->PlaceLog->save(array('PlaceLog' => array(
                        'id' => $this->Place->getNewUUID(),
                        'house_id' => $dataToSave['Place']['id'],
                        'status' => $dataToSave['Place']['status'],
                        'date_visited' => $dataToSave['PlaceLog']['date_visited'],
                        'created_by' => $this->loginMember['id'],
                        'note' => $dataToSave['PlaceLog']['note'],
                )));
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'view', bin2hex($dataToSave['Place']['id'])));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_edit($id = null) {
        if (!empty($id)) {
            $item = $this->Place->read(null, hex2bin($id));
        }
        if (empty($item)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }
            $this->Place->id = $item['Place']['id'];
            $dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            if ($this->Place->save($dataToSave)) {
                $this->Place->PlaceLog->create();
                $this->Place->PlaceLog->save(array('PlaceLog' => array(
                        'id' => $this->Place->getNewUUID(),
                        'house_id' => $id,
                        'status' => $dataToSave['Place']['status'],
                        'date_visited' => $dataToSave['PlaceLog']['date_visited'],
                        'created_by' => $this->loginMember['id'],
                        'note' => $dataToSave['PlaceLog']['note'],
                )));
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'view', bin2hex($id)));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('id', $id);
        $this->data = $item;
    }

    function admin_delete($id = null) {
        if (!empty($id)) {
            if ($this->Place->delete(hex2bin($id))) {
                $this->Session->setFlash(__('The data has been deleted', true));
            } else {
                $this->Session->setFlash(__('Please do following links in the page', true));
            }
        } else {
            $this->Session->setFlash(__('Please do following links in the page', true));
        }
        $this->redirect(array('action' => 'index'));
    }

}
