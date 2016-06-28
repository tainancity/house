<?php

App::uses('AppController', 'Controller');

class TasksController extends AppController {

    public $name = 'Tasks';
    public $paginate = array();
    public $helpers = array();

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();


        $habtmKeys = array(
            'Group' => 'group_id',
        );
        $foreignKeys = array_merge($habtmKeys, $foreignKeys);

        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Task.' . $foreignKeys[$foreignModel]] = $foreignId;

            $joins = array(
                'Group' => array(
                    0 => array(
                        'table' => 'groups_tasks',
                        'alias' => 'GroupsTask',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.Task_id = Task.id'),
                    ),
                    1 => array(
                        'table' => 'groups',
                        'alias' => 'Group',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.Group_id = Group.id'),
                    ),
                ),
            );
            if (array_key_exists($foreignModel, $habtmKeys)) {
                unset($scope['Task.' . $foreignKeys[$foreignModel]]);
                if ($op != 'set') {
                    $scope[$joins[$foreignModel][0]['alias'] . '.' . $foreignKeys[$foreignModel]] = $foreignId;
                    $this->paginate['Task']['joins'] = $joins[$foreignModel];
                }
            }
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->paginate['Task']['limit'] = 20;
        $items = $this->paginate($this->Task, $scope);

        if ($op == 'set' && !empty($joins[$foreignModel]) && !empty($foreignModel) && !empty($foreignId) && !empty($items)) {
            foreach ($items AS $key => $item) {
                $items[$key]['option'] = $this->Task->find('count', array(
                    'joins' => $joins[$foreignModel],
                    'conditions' => array(
                        'Task.id' => $item['Task']['id'],
                        $foreignModel . '.id' => $foreignId,
                    ),
                ));
                if ($items[$key]['option'] > 0) {
                    $items[$key]['option'] = 1;
                }
            }
            $this->set('op', $op);
        }

        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_view($id = null) {
        if (!$id || !$this->data = $this->Task->read(null, $id)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Task->create();
            if ($this->Task->save($this->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            if ($this->Task->save($this->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('id', $id);
        $this->data = $this->Task->read(null, $id);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依照網址指示操作');
        } else if ($this->Task->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

    function admin_report($id = null, $model = 'Land') {
        if (!empty($id)) {
            $task = $this->Task->read(null, $id);
        }
        if($model !== 'Land') {
            $model = 'House';
            $label = '空屋總數';
        } else {
            $label = '空地總數';
        }
        $this->layout = 'print';
        if (!empty($task)) {
            $groups = $this->Task->Group->find('list');
            $places = $this->Task->Place->find('all', array(
                'conditions' => array(
                    'Place.task_id' => $id,
                    'Place.model' => $model,
                ),
                'fields' => array('group_id', 'status', 'is_adopt', 'adopt_type', 'area'),
                'order' => array('Place.group_id' => 'ASC'),
            ));
            $report = array();
            foreach ($places AS $place) {
                if (!isset($report[$place['Place']['group_id']])) {
                    $report[$place['Place']['group_id']] = array(
                        '區別' => $groups[$place['Place']['group_id']],
                        $label => 0,
                        '總面積' => 0,
                        '現況良好數量' => 0,
                        '待改善數量' => 0,
                        '認養地數量' => 0,
                        '認養地面積' => 0,
                        '綠美化數量' => 0,
                        '綠美化面積' => 0,
                        '停車場數量' => 0,
                        '停車場面積' => 0,
                        '運動場數量' => 0,
                        '運動場面積' => 0,
                        '其他公益場地數量' => 0,
                        '其他公益場地面積' => 0,
                    );
                }
                $report[$place['Place']['group_id']][$label] += 1;
                $report[$place['Place']['group_id']]['總面積'] += $place['Place']['area'];
                if ($place['Place']['status'] == 1) {
                    $report[$place['Place']['group_id']]['現況良好數量'] += 1;
                } else {
                    $report[$place['Place']['group_id']]['待改善數量'] += 1;
                }
                if ($place['Place']['is_adopt'] == 1) {
                    $report[$place['Place']['group_id']]['認養地數量'] += 1;
                    $report[$place['Place']['group_id']]['認養地面積'] += $place['Place']['area'];
                    switch ($place['Place']['adopt_type']) {
                        case '綠美化':
                            $report[$place['Place']['group_id']]['綠美化數量'] += 1;
                            $report[$place['Place']['group_id']]['綠美化面積'] += $place['Place']['area'];
                            break;
                        case '停車場':
                            $report[$place['Place']['group_id']]['停車場數量'] += 1;
                            $report[$place['Place']['group_id']]['停車場面積'] += $place['Place']['area'];
                            break;
                        case '運動場':
                            $report[$place['Place']['group_id']]['運動場數量'] += 1;
                            $report[$place['Place']['group_id']]['運動場面積'] += $place['Place']['area'];
                            break;
                        case '其他公益場地':
                            $report[$place['Place']['group_id']]['其他公益場地數量'] += 1;
                            $report[$place['Place']['group_id']]['其他公益場地面積'] += $place['Place']['area'];
                            break;
                    }
                }
            }
            $this->set('report', $report);
        }
    }

}
