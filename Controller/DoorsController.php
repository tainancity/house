<?php

App::uses('AppController', 'Controller');

class DoorsController extends AppController {

    public $name = 'Doors';
    public $paginate = array();
    public $helpers = array();

    public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('q');
        }
    }

    public function q($address = '') {
        $this->autoRender = false;
        $this->response->type('json');
        $result = array();
        if (!empty($address)) {
            $result = $this->Door->find('all', array(
                'conditions' => $this->Door->extractAddress($address),
                'limit' => 10,
            ));
            foreach ($result AS $k => $item) {
                $result[$k]['Door']['id'] = bin2hex($result[$k]['Door']['id']);
            }
        }
        if (!isset($_GET['pretty'])) {
            $this->response->body(json_encode($result));
        } else {
            $this->response->body(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    function index() {
        $this->paginate['Door'] = array(
            'limit' => 20,
        );
        $this->set('items', $this->paginate($this->Door));
    }

    function view($id = null) {
        if (!$id || !$this->data = $this->Door->read(null, $id)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_index() {
        $this->paginate['Door'] = array(
            'limit' => 20,
        );
        $this->set('items', $this->paginate($this->Door));
    }

    function admin_view($id = null) {
        if (!$id || !$this->data = $this->Door->read(null, $id)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Door->create();
            if ($this->Door->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Please do following links in the page', true));
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            if ($this->Door->save($this->data)) {
                $this->Session->setFlash(__('The data has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Something was wrong during saving, please try again', true));
            }
        }
        $this->set('id', $id);
        $this->data = $this->Door->read(null, $id);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Please do following links in the page', true));
        } else if ($this->Door->delete($id)) {
            $this->Session->setFlash(__('The data has been deleted', true));
        }
        $this->redirect(array('action' => 'index'));
    }

}
