<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $helpers = array('Html', 'Form', 'Js', 'Session');
    public $components = array('Acl', 'Auth', 'RequestHandler', 'Session');

    public function beforeFilter() {
        if (isset($this->Auth)) {
            $this->Auth->authenticate = array(
                'Form' => array(
                    'userModel' => 'Member',
                    'scope' => array('Member.user_status' => 'Y'),
                )
            );
            $this->Auth->loginAction = '/members/login';
            $this->Auth->loginRedirect = '/';
            $this->Auth->authorize = array(
                'Actions' => array(
                    'userModel' => 'Member',
                )
            );
        }
        $this->loginMember = $this->Session->read('Auth.User');
        if (empty($this->loginMember)) {
            $this->loginMember = array(
                'id' => 0,
                'group_id' => 0,
                'username' => '',
            );
        } else {
            if (!isset($this->loginMember['Task'])) {
                $memberModel = ClassRegistry::init('Member');
                $this->Session->write('Auth.User.Task', $memberModel->Group->GroupsTask->find('list', array(
                            'conditions' => array('group_id' => $this->loginMember['group_id']),
                            'fields' => array('task_id', 'task_id'),
                )));
                $this->loginMember = $this->Session->read('Auth.User');
            }
        }

        Configure::write('loginMember', $this->loginMember);
        $this->set('loginMember', $this->loginMember);
    }

}
