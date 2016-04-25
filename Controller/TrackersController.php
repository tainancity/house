<?php

App::uses('AppController', 'Controller');

class TrackersController extends AppController {

    public $name = 'Trackers';
    public $paginate = array();
    public $helpers = array('Olc', 'Media.Media');

    function admin_index($projectId = 0) {
        $projectId = intval($projectId);
        $project = $this->Tracker->Project->read(null, $projectId);
        if (empty($project)) {
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
        $this->set('project', $project);
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

    function admin_import($projectId = 0, $meters = 0, $latitude = 0, $longitude = 0) {
        $this->autoRender = false;
        $this->response->type('json');
        $projectId = intval($projectId);
        $latitude = floatval($latitude);
        $longitude = floatval($longitude);
        $meters = intval($meters);
        $result = array(
            'queryString' => array(
                $projectId,
                $meters,
                $latitude,
                $longitude,
            ),
            'result' => array(),
        );
        if ($projectId > 0 && $meters > 0) {
            // info from http://gis.stackexchange.com/questions/19760/how-do-i-calculate-the-bounding-box-for-given-a-distance-and-latitude-longitude
            $distance = $meters / 2 * 0.0000089982311916;
            $items = $this->Tracker->Place->find('all', array(
                'fields' => array('Place.id', 'Place.group_id'),
                'conditions' => array(
                    'Place.latitude >' => $latitude - $distance,
                    'Place.latitude <' => $latitude + $distance,
                    'Place.longitude >' => $longitude - $distance,
                    'Place.longitude <' => $longitude + $distance,
                ),
            ));
            foreach ($items AS $k => $item) {
                // check if the place existed
                $countTracker = $this->Tracker->find('count', array(
                    'conditions' => array(
                        'Tracker.project_id' => $projectId,
                        'Tracker.place_id' => $items[$k]['Place']['id'],
                    ),
                ));
                if ($countTracker === 0) {
                    $dataToSave = array(
                        'Tracker' => array(),
                    );
                    $dataToSave['Tracker']['id'] = $this->Tracker->getNewUUID();
                    $dataToSave['Tracker']['project_id'] = $projectId;
                    $dataToSave['Tracker']['created_by'] = $this->loginMember['id'];
                    $dataToSave['Tracker']['place_id'] = $items[$k]['Place']['id'];
                    $dataToSave['Tracker']['group_id'] = $items[$k]['Place']['group_id'];
                    $this->Tracker->create();
                    $this->Tracker->save($dataToSave);
                }
                $items[$k]['Place']['id'] = bin2hex($items[$k]['Place']['id']);
            }
            $result['result'] = $items;
        }
        if (!isset($_GET['pretty'])) {
            $this->response->body(json_encode($result));
        } else {
            $this->response->body(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
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
