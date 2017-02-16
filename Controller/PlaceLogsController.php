<?php

App::uses('AppController', 'Controller');

class PlaceLogsController extends AppController {

    public $name = 'PlaceLogs';
    public $paginate = array();
    public $helpers = array('Media.Media');

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'Place' => 'Place_id',
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            if (!empty($this->data)) {
                $this->data['PlaceLog'][$foreignKeys[$foreignModel]] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (!empty($this->data)) {
            $this->PlaceLog->create();
            if ($this->PlaceLog->save($this->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);

        $belongsToModels = array(
            'listPlace' => array(
                'label' => '房屋',
                'modelName' => 'Place',
                'foreignKey' => 'Place_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            if ($foreignModel == $model['modelName']) {
                unset($belongsToModels[$key]);
                continue;
            }
            $this->set($key, $this->PlaceLog->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }
	
	
	function admin_delete($id = null) {
        if (!empty($id)) {
			
			$item = $this->PlaceLog->find('first', array(
					'conditions' => array('id' => $id),    
				));	
            if ($this->PlaceLog->delete($id)) {
				//unlink(WWW_ROOT."media/transfer/".$item['PlaceLog']['dirname']."/".$item['PlaceLog']['basename']);//此檔案,系統會自動耦合刪除(有關連)
				unlink(WWW_ROOT."media/filter/original/".$item['PlaceLog']['dirname']."/".$item['PlaceLog']['basename']);
				unlink(WWW_ROOT."media/filter/l/".$item['PlaceLog']['dirname']."/".$item['PlaceLog']['basename']);
				unlink(WWW_ROOT."media/filter/m/".$item['PlaceLog']['dirname']."/".$item['PlaceLog']['basename']);
				//unlink(WWW_ROOT."media/filter/s/".$item['PlaceLog']['dirname']."/".$item['PlaceLog']['basename']);
				
                $this->Session->setFlash('資料已經刪除');
            } else {
                $this->Session->setFlash('請依照網址指示操作');
            }
        } else {
            $this->Session->setFlash('請依照網址指示操作');
        }
		$this->redirect(array('controller' => 'Places','action' => 'view', $this->request->query('place_id')));
    }
	
	
	function admin_index() {
		

		$this->paginate['PlaceLog']['limit'] = 20;
		$this->paginate['PlaceLog']['order'] = array('created' => 'DESC');
        $this->paginate['PlaceLog']['contain'] = array(
            'Creator' => array(
				'fields' => array('username'),
			),
			'Place',
			'Place.Group' => array(
				'fields' => array('name'),
			),
			'Place.Task' => array(
				'fields' => array('title'),
			),
			
        );
		$item=$this->paginate($this->PlaceLog);
		$this->set('item',$item);
        if (!empty($item)) {
            $this->set('item', $item);
	
        } else {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }
}
