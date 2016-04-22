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
                    'Project' => array(
                        'fields' => array('title'),
                    ),
                    'Creator' => array(
                        'fields' => array('username'),
                    ),
                    'Completer' => array(
                        'fields' => array('username'),
                    ),
                    'Place' => array(
                        'fields' => array('title'),
                    ),
                ),
            ));
            $item['Tracker']['id'] = bin2hex($item['Tracker']['id']);
            $item['Tracker']['place_id'] = bin2hex($item['Tracker']['place_id']);
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
