<?php

/**
 * @property Member Member
 *
 */
class MembersController extends AppController {

    public $name = 'Members';
    public $paginate = array();

    public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('login', 'logout', 'setup');
        }
    }

    public function login() {
        if (!$this->Member->hasAny()) {
            $this->redirect(array('action' => 'setup'));
        }
        if (!empty($this->request->data['Member']['username'])) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Username or password is wrong', true));
            }
        }
    }

    public function logout() {
        $this->Auth->logout();
        $this->redirect(array('action' => 'login'));
    }

    public function setup() {
        if ($this->Member->hasAny(array('user_status' => 'Y'))) {
            $this->Session->setFlash(__('There are members in database. If you want to reset, please remove them first.', true));
            $this->redirect('/members/login');
        } elseif (!empty($this->request->data)) {
            $this->loadModel('Group');
            $this->request->data['Group']['name'] = 'Admin';
            $this->request->data['Group']['parent_id'] = 0;
            $this->Group->create();
            if ($this->Group->save($this->request->data)) {
                $this->request->data['Member']['group_id'] = $this->Group->id;
                $this->request->data['Member']['user_status'] = 'Y';
                $this->Member->create();
                if ($this->Member->save($this->request->data)) {
                    $this->loadModel('Permissible.PermissibleAro');
                    $this->PermissibleAro->reset();
                    $this->loadModel('Permissible.PermissibleAco');
                    if ($this->PermissibleAco->reset()) {
                        $this->Acl->deny('everyone', 'app');
                        $this->Acl->allow('Group1', 'app');
                    }
                    $this->Session->setFlash(__('The administrator created, please login with the id/password you entered.', true));
                    $this->redirect('/members/login');
                } else {
                    $this->Session->setFlash(__('Administrator created failed.', true));
                }
            } else {
                $this->Session->setFlash(__('Administrator created failed.', true));
            }
        }
    }

    public function admin_index() {
        $scope = array();
        $keyword = '';
        if (isset($this->params['named']['keyword'])) {
            if (is_array($this->params['named']['keyword'])) {
                foreach ($this->params['named']['keyword'] AS $keyword) {
                    continue;
                }
            } else {
                $keyword = $this->params['named']['keyword'];
            }
            $this->Session->write('Members.index.keyword', $keyword);
        } else {
            $keyword = $this->Session->read('Members.index.keyword');
        }
        if (!empty($keyword)) {
            $scope['OR'] = array(
                'Member.username LIKE' => '%' . $keyword . '%',
                'Group.name LIKE' => '%' . $keyword . '%',
            );
        }
        $this->paginate['Member'] = array(
            'order' => array('Member.id DESC'),
            'contain' => array('Group'),
            'limit' => 40,
        );
        $this->set('members', $this->paginate($this->Member, $scope));
        $this->set('keyword', $keyword);
    }

    public function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('member', $this->Member->read(null, $id));
    }

    public function admin_add() {
        if (!empty($this->request->data)) {
            $this->Member->create();
            if ($this->Member->save($this->request->data)) {
                $this->Acl->Aro->saveField('alias', 'Member/' . $this->Member->getInsertID());
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('groups', $this->Member->Group->find('list'));
    }

    public function admin_edit($id = null) {
        if (!empty($id)) {
            $member = $this->Member->read(null, $id);
        }
        if (empty($member)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data)) {
            $oldgroupid = $member['Member']['group_id'];
            $dataToSave = $this->request->data;
            if (empty($dataToSave['Member']['password'])) {
                unset($dataToSave['Member']['password']);
            }
            if ($this->Member->save($dataToSave)) {
                if ($oldgroupid !== $dataToSave['Member']['group_id']) {
                    $aro = & $this->Acl->Aro;
                    $memberAro = $aro->findByForeignKeyAndModel($member['Member']['id'], 'Member');
                    $groupAro = $aro->findByForeignKeyAndModel($dataToSave['Member']['group_id'], 'Group');
                    $aro->id = $memberAro['Aro']['id'];
                    $aro->save(array('parent_id' => $groupAro['Aro']['id']));
                }
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $member;
        }
        $this->set('groups', $this->Member->Group->find('list'));
    }

    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Member->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function admin_acos() {
        $this->loadModel('Permissible.PermissibleAco');
        $this->PermissibleAco->refresh();
        $this->redirect($this->referer());
    }

}
