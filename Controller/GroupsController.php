<?php

/**
 * @property Group Group
 */
class GroupsController extends AppController {

    public $name = 'Groups';
    public $paginate = array();

    public function admin_index($parentId = 0) {
        $this->paginate['Group'] = array(
            'contain' => array(),
        );
        $this->set('parentId', $parentId);
        $upperLevelId = 0;
        if ($parentId > 0) {
            $upperLevelId = $this->Group->field('parent_id', array('Group.id' => $parentId));
        }
        $this->set('upperLevelId', $upperLevelId);
        if (!$groups = $this->paginate($this->Group, array('parent_id' => $parentId))) {
            if (isset($this->params['named']['page']) && $this->params['named']['page'] > 1) {
                $this->Session->setFlash(__('The page doesn\'t exists', true));
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('It doesn\'t have sub groups. You could try to add one through the following form.', true));
                $this->redirect(array('action' => 'add', $parentId));
            }
        } else {
            $this->set('groups', $groups);
        }
        $this->set('url', array($parentId));
    }

    function admin_tasks($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();


        $habtmKeys = array(
            'Task' => 'task_id',
        );
        $foreignKeys = array_merge($habtmKeys, $foreignKeys);

        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Task.' . $foreignKeys[$foreignModel]] = $foreignId;

            $joins = array(
                'Task' => array(
                    0 => array(
                        'table' => 'groups_tasks',
                        'alias' => 'GroupsTask',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.group_id = Group.id'),
                    ),
                    1 => array(
                        'table' => 'tasks',
                        'alias' => 'Task',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.task_id = Task.id'),
                    ),
                ),
            );
            if (array_key_exists($foreignModel, $habtmKeys)) {
                unset($scope['Task.' . $foreignKeys[$foreignModel]]);
                if ($op != 'set') {
                    $scope[$joins[$foreignModel][0]['alias'] . '.' . $foreignKeys[$foreignModel]] = $foreignId;
                    $this->paginate['Group']['joins'] = $joins[$foreignModel];
                }
            }
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->paginate['Group']['limit'] = 20;
        $items = $this->paginate($this->Group, $scope);

        if ($op == 'set' && !empty($joins[$foreignModel]) && !empty($foreignModel) && !empty($foreignId) && !empty($items)) {
            foreach ($items AS $key => $item) {
                $items[$key]['option'] = $this->Group->find('count', array(
                    'joins' => $joins[$foreignModel],
                    'conditions' => array(
                        'Group.id' => $item['Group']['id'],
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

    function admin_habtmSet($foreignModel = null, $foreignId = 0, $id = 0, $switch = null) {
        $habtmKeys = array(
            'Task' => array(
                'associationForeignKey' => 'task_id',
                'foreignKey' => 'group_id',
                'alias' => 'GroupsTask',
            ),
        );
        $foreignModel = array_key_exists($foreignModel, $habtmKeys) ? $foreignModel : null;
        $foreignId = intval($foreignId);
        $id = intval($id);
        $switch = in_array($switch, array('on', 'off')) ? $switch : null;
        if (empty($foreignModel) || $foreignId <= 0 || $id <= 0 || empty($switch)) {
            $this->set('habtmMessage', __('Wrong Parameters'));
        } else {
            $this->Group->id = $id;
            $habtmModel = &$this->Group->$habtmKeys[$foreignModel]['alias'];
            $conditions = array(
                $habtmKeys[$foreignModel]['associationForeignKey'] => $foreignId,
                $habtmKeys[$foreignModel]['foreignKey'] => $id,
                'title' => $this->Group->field('name'),
            );
            $status = ($habtmModel->find('count', array(
                        'conditions' => $conditions,
                    ))) ? 'on' : 'off';
            if ($status == $switch) {
                $this->set('habtmMessage', __('Duplicated operactions', true));
            } else if ($switch == 'on') {
                $habtmModel->create();
                if ($habtmModel->save(array($habtmKeys[$foreignModel]['alias'] => $conditions))) {
                    $this->set('habtmMessage', __('Updated', true));
                } else {
                    $this->set('habtmMessage', __('Update failed', true));
                }
            } else {
                if ($habtmModel->deleteAll($conditions)) {
                    $this->set('habtmMessage', __('Updated', true));
                } else {
                    $this->set('habtmMessage', __('Update failed', true));
                }
            }
        }
    }

    public function admin_add($parentId = 0) {
        if (!empty($this->request->data)) {
            $this->Group->create();
            $this->request->data['Group']['parent_id'] = $parentId;
            if ($this->Group->save($this->request->data)) {
                $this->Acl->Aro->saveField('alias', 'Group' . $this->Group->getInsertID());
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index', $parentId));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('parentId', $parentId);
    }

    public function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__('Please select a group first!', true));
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data)) {
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index', $this->Group->field('parent_id')));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->Group->read(null, $id);
        }
    }

    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Please select a group first!', true));
            $this->redirect($this->referer());
        }
        $parentId = $this->Group->field('parent_id', array('Group.parent_id' => $id));
        if ($this->Group->delete($id)) {
            $this->Session->setFlash(__('The group has been removed', true));
            $this->redirect(array('action' => 'index', $parentId));
        }
    }

    public function admin_acos($groupId = 0) {
        if (empty($groupId) || !$aroGroup = $this->Group->find('first', array(
            'fields' => array('Group.id'),
            'conditions' => array(
                'Group.id' => $groupId,
            ),
                ))) {
            $this->Session->setFlash(__('Please select a group first!', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $count = 0;
            foreach ($this->data AS $key => $val) {
                if (strstr($key, '___')) {
                    $key = str_replace('___', '/', $key);
                    if ($val == '+') {
                        $this->Acl->allow($aroGroup, $key);
                        ++$count;
                    } elseif ($val == '-') {
                        $this->Acl->deny($aroGroup, $key);
                        ++$count;
                    }
                }
            }
            if ($count > 0) {
                $this->Session->setFlash(sprintf(__('%d items updated successfully!', true), $count));
            }
        }
        $this->set('groupId', $groupId);
        /*
         * Find the root node of ACOS
         */
        $aco = & $this->Acl->Aco;
        $acoRoot = $aco->node('app');
        if (!empty($acoRoot)) {
            $acos = $this->Acl->Aco->find('all', array(
                'conditions' => array('Aco.parent_id' => $acoRoot[0]['Aco']['id']),
            ));
            foreach ($acos AS $key => $controllerAco) {
                $actionAcos = $this->Acl->Aco->find('all', array(
                    'conditions' => array(
                        'Aco.parent_id' => $controllerAco['Aco']['id'],
                    ),
                ));
                if (!empty($actionAcos)) {
                    foreach ($actionAcos AS $actionAco) {
                        if (($actionAco['Aco']['rght'] - $actionAco['Aco']['lft']) != 1) {
                            /*
                             * Controller in plugins
                             */
                            $pluginAcos = $this->Acl->Aco->find('all', array(
                                'conditions' => array(
                                    'Aco.parent_id' => $actionAco['Aco']['id'],
                                ),
                            ));
                            foreach ($pluginAcos AS $pluginAco) {
                                $pluginAco['Aco']['permitted'] = $this->Acl->check(
                                        $aroGroup, $controllerAco['Aco']['alias']
                                        . '/' . $actionAco['Aco']['alias']
                                        . '/' . $pluginAco['Aco']['alias']
                                );
                                $pluginAco['Aco']['alias'] = $actionAco['Aco']['alias']
                                        . '/' . $pluginAco['Aco']['alias'];
                                $acos[$key]['Aco']['Aco'][] = $pluginAco['Aco'];
                            }
                        } else {
                            $actionAco['Aco']['permitted'] = $this->Acl->check(
                                    $aroGroup, $controllerAco['Aco']['alias']
                                    . '/' . $actionAco['Aco']['alias']
                            );
                            $acos[$key]['Aco']['Aco'][] = $actionAco['Aco'];
                        }
                    }
                }
            }
            $this->set('acos', $acos);
        } else {
            /**
             *  Can't find the root node, forward to members/setup method
             */
            $this->redirect('/members/setup');
        }
    }

}
