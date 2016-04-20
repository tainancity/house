<?php

App::uses('AppController', 'Controller');

class PlacesController extends AppController {

    public $name = 'Places';
    public $paginate = array();
    public $helpers = array('Olc', 'Media.Media');

    function admin_index($typeModel = 'Door', $foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();
        if (!in_array($typeModel, array('Door', 'Land'))) {
            $typeModel = 'Door';
        }

        $foreignKeys = array(
            'Group' => 'group_id',
            'Task' => 'task_id',
        );

        $scope = array(
            'Place.model' => $typeModel,
        );
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
        $this->set('typeModel', $typeModel);
        $this->set('groups', $this->Place->Group->find('list'));
        $this->set('tasks', $this->Place->Task->find('list'));
        $this->set('url', array($typeModel, $foreignModel, $foreignId));
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
            if ($item['Place']['model'] === 'Land') {
                $land = $this->Place->Land->read(null, $item['Place']['foreign_id']);
                if (isset($land['Land'])) {
                    $item['Land'] = $land['Land'];
                }
            }
        }
        if (!empty($item)) {
            $this->set('item', $item);
        } else {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add($typeModel = 'Door', $foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
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
            if (!empty($dataToSave['PlaceLog']['file']['name'])) {
                $p = pathinfo($dataToSave['PlaceLog']['file']['name']);
                $dataToSave['PlaceLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
            }
            if (!empty($foreignModel)) {
                $dataToSave['Place'][$foreignKeys[$foreignModel]] = $foreignId;
            }

            $dataToSave['Place']['id'] = $this->Place->getNewUUID();
            $dataToSave['Place']['model'] = $typeModel;
            if (!empty($dataToSave['Place']['foreign_id'])) {
                $dataToSave['Place']['foreign_id'] = hex2bin($dataToSave['Place']['foreign_id']);
            }
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }

            $dataToSave['Place']['created_by'] = $dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            $this->Place->create();
            if ($this->Place->save($dataToSave)) {
                $dataToSave['PlaceLog']['id'] = $this->Place->getNewUUID();
                $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                $dataToSave['PlaceLog']['place_id'] = $dataToSave['Place']['id'];
                $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
                $this->Place->PlaceLog->create();
                $this->Place->PlaceLog->save($dataToSave);
                $this->Session->setFlash('資料已經儲存');
                if (!$this->request->isAjax()) {
                    $this->redirect(array('action' => 'view', bin2hex($dataToSave['Place']['id'])));
                } else {
                    echo json_encode(array(
                        'id' => bin2hex($dataToSave['Place']['id']),
                        'title' => $dataToSave['Place']['title'],
                    ));
                    exit();
                }
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('task', $this->Place->Task->read(null, $foreignId));
        $this->set('typeModel', $typeModel);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_import($typeModel = 'Door', $taskId = '') {
        if (empty($taskId)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect('/');
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('typeModel', $typeModel);
        $this->set('task', $this->Place->Task->read(null, $taskId));
    }

    function admin_edit($id = null) {
        if (!empty($id)) {
            $item = $this->Place->read(null, hex2bin($id));
        }
        if (!empty($item)) {
            if ($item['Place']['model'] === 'Land') {
                $land = $this->Place->Land->read(null, $item['Place']['foreign_id']);
                if (isset($land['Land'])) {
                    $item['Land'] = $land['Land'];
                }
            }
            $item['Place']['foreign_id'] = bin2hex($item['Place']['foreign_id']);
        } else {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            if (!empty($dataToSave['PlaceLog']['file']['name'])) {
                $p = pathinfo($dataToSave['PlaceLog']['file']['name']);
                $dataToSave['PlaceLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
            }
            if (!empty($dataToSave['Place']['foreign_id'])) {
                $dataToSave['Place']['foreign_id'] = hex2bin($dataToSave['Place']['foreign_id']);
            }
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }
            $this->Place->id = $item['Place']['id'];
            $dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            $dataToSave['Place']['modified'] = date('Y-m-d H:i:s');
            if ($this->Place->save($dataToSave)) {
                $dataToSave['PlaceLog']['id'] = $this->Place->getNewUUID();
                $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                $dataToSave['PlaceLog']['place_id'] = hex2bin($id);
                $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
                $this->Place->PlaceLog->create();
                $this->Place->PlaceLog->save($dataToSave);
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('task', $this->Place->Task->read(null, $item['Place']['task_id']));
        $this->set('id', $id);
        $this->data = $item;
    }

    function admin_delete($id = null) {
        if (!empty($id)) {
            if ($this->Place->delete(hex2bin($id))) {
                $this->Session->setFlash('資料已經刪除');
            } else {
                $this->Session->setFlash('請依照網址指示操作');
            }
        } else {
            $this->Session->setFlash('請依照網址指示操作');
        }
        $this->redirect(array('action' => 'index'));
    }

}
