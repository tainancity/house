<?php

App::uses('AppController', 'Controller');

class ProjectsController extends AppController {

    public $name = 'Projects';
    public $paginate = array();
    public $helpers = array();

    function admin_index() {
        $this->paginate['Project']['limit'] = 20;
        $items = $this->paginate($this->Project);
        $this->set('items', $items);
    }

    function admin_view($id = null) {
        if (!$id || !$this->data = $this->Project->read(null, $id)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add() {
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            $dataToSave['Project']['created_by'] = $dataToSave['Project']['modified_by'] = $this->loginMember['id'];
            $this->Project->create();
            if ($this->Project->save($dataToSave)) {
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
            $dataToSave = $this->data;
            $dataToSave['Project']['id'] = $id;
            $dataToSave['Project']['modified_by'] = $this->loginMember['id'];
            if ($this->Project->save($dataToSave)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('id', $id);
        $this->data = $this->Project->read(null, $id);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依照網址指示操作');
        } else if ($this->Project->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}
