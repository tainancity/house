<?php

App::uses('AppController', 'Controller');

class DoorsController extends AppController {

    public $name = 'Doors';
    public $paginate = array();
    public $helpers = array();

    public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow(array('q', 'index'));
        }
    }

    public function q($address = '') {
        $this->autoRender = false;
        $this->response->type('json');
        if (!empty($address)) {
            $result = $this->Door->queryKeyword($address);
        }
        if (!isset($_GET['pretty'])) {
            $this->response->body(json_encode($result));
        } else {
            $this->response->body(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    public function index() {
        
    }

}
