<?php

App::uses('AppController', 'Controller');

class TrackersController extends AppController {

    public $name = 'Trackers';
    public $paginate = array();
    public $helpers = array('Olc', 'Media.Media');

    function admin_index($projectId = 0) {
        $projectId = intval($projectId);
        if ($projectId <= 0) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
        $this->paginate['Tracker']['limit'] = 20;
        $this->paginate['Tracker']['contain'] = array(
            'Place' => array(
                'fields' => array('id', 'title'),
            ),
        );
        $items = $this->paginate($this->Tracker);
        foreach ($items AS $k => $item) {
            $items[$k]['Tracker']['id'] = bin2hex($items[$k]['Tracker']['id']);
        }
        $this->set('groups', $this->Tracker->Group->find('list'));
        $this->set('items', $items);
        $this->set('projectId', $projectId);
        $this->set('url', array($projectId));
    }

    function admin_view($id = null) {
        if (!empty($id)) {
            $id = hex2bin($id);
            $item = $this->Tracker->find('first', array(
                'conditions' => array('Tracker.id' => $id),
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
                    'TrackerLog' => array(
                        'order' => array('TrackerLog.created' => 'DESC'),
                        'Creator' => array(
                            'fields' => array('username'),
                        ),
                    ),
                ),
            ));
            $item['Tracker']['id'] = bin2hex($item['Tracker']['id']);
            if ($item['Tracker']['model'] === 'Land') {
                $land = $this->Tracker->Land->read(null, $item['Tracker']['foreign_id']);
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

    function admin_add($projectId = 0) {
        $projectId = intval($projectId);
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            $dataToSave['Tracker']['id'] = $this->Tracker->getNewUUID();
            $dataToSave['Tracker']['project_id'] = $projectId;
            $dataToSave['Tracker']['created_by'] = $this->loginMember['id'];
            $dataToSave['Tracker']['place_id'] = hex2bin($dataToSave['Tracker']['place_id']);
            $this->Tracker->create();
            if ($this->Tracker->save($dataToSave)) {
                if (!$this->request->isAjax()) {
                    $this->redirect(array('action' => 'view', bin2hex($dataToSave['Tracker']['id'])));
                } else {
                    echo json_encode(array(
                        'id' => bin2hex($dataToSave['Tracker']['id']),
                    ));
                    exit();
                }
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('project', $this->Tracker->Project->read(null, $projectId));
    }

    function admin_import($projectId = '') {
        if (empty($projectId)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect('/');
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Tracker->Group->find('list'));
        }
        $this->set('project', $this->Tracker->Project->read(null, $projectId));
    }

    function admin_edit($id = null) {
        if (!empty($id)) {
            $item = $this->Tracker->read(null, hex2bin($id));
        }
        if (!empty($item)) {
            if ($item['Tracker']['model'] === 'Land') {
                $land = $this->Tracker->Land->read(null, $item['Tracker']['foreign_id']);
                if (isset($land['Land'])) {
                    $item['Land'] = $land['Land'];
                }
            }
            $item['Tracker']['foreign_id'] = bin2hex($item['Tracker']['foreign_id']);
        } else {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            if (!empty($dataToSave['TrackerLog']['file']['name'])) {
                $p = pathinfo($dataToSave['TrackerLog']['file']['name']);
                $dataToSave['TrackerLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
            }
            if (!empty($dataToSave['Tracker']['foreign_id'])) {
                $dataToSave['Tracker']['foreign_id'] = hex2bin($dataToSave['Tracker']['foreign_id']);
            }
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Tracker']['group_id'] = $this->loginMember['group_id'];
            }
            $this->Tracker->id = $item['Tracker']['id'];
            $dataToSave['Tracker']['modified_by'] = $this->loginMember['id'];
            $dataToSave['Tracker']['modified'] = date('Y-m-d H:i:s');
            if ($this->Tracker->save($dataToSave)) {
                $dataToSave['TrackerLog']['id'] = $this->Tracker->getNewUUID();
                $dataToSave['TrackerLog']['status'] = $dataToSave['Tracker']['status'];
                $dataToSave['TrackerLog']['tracker_id'] = hex2bin($id);
                $dataToSave['TrackerLog']['created_by'] = $this->loginMember['id'];
                $this->Tracker->TrackerLog->create();
                $this->Tracker->TrackerLog->save($dataToSave);
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Tracker->Group->find('list'));
        }
        $this->set('task', $this->Tracker->Task->read(null, $item['Tracker']['task_id']));
        $this->set('id', $id);
        $this->data = $item;
    }

    function admin_delete($id = null) {
        if (!empty($id)) {
            $tracker = $this->Tracker->read(array('id', 'project_id'), hex2bin($id));
            if ($this->Tracker->delete($tracker['Tracker']['id'])) {
                $this->Session->setFlash('資料已經刪除');
            } else {
                $this->Session->setFlash('請依照網址指示操作');
            }
            $this->redirect(array('action' => 'index', $tracker['Tracker']['project_id']));
        } else {
            $this->Session->setFlash('請依照網址指示操作');
        }
        $this->redirect('/');
    }

}
