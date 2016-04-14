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
        $result = array(
            'queryString' => $address,
            'result' => array(),
        );
        if (!empty($address)) {
            $doors = $this->Door->find('all', array(
                'conditions' => $this->Door->extractAddress($address),
                'limit' => 10,
            ));
            foreach ($doors AS $k => $item) {
                $item['Door']['id'] = bin2hex($item['Door']['id']);
                $item['Door']['lin'] = intval($item['Door']['lin']);
                $item['Door']['label'] = $item['Door']['value'] = "{$item['Door']['area']}{$item['Door']['cunli']}{$item['Door']['lin']}鄰{$item['Door']['road']}{$item['Door']['place']}{$item['Door']['lane']}{$item['Door']['alley']}{$item['Door']['number']}";
                $result['result'][] = $item['Door'];
            }
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
