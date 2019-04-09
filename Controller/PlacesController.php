<?php

App::uses('AppController', 'Controller');

//http://stackoverflow.com/questions/28518238/how-can-i-use-my-own-external-class-in-cakephp-3-0
//use geometry\geometry;
require_once(ROOT .DS. 'house'.DS. 'Vendor' . DS . 'geometry' . DS . 'geometry.php');
ini_set('memory_limit', '256M');

class PlacesController extends AppController {

    public $name = 'Places';
    public $paginate = array();
    public $helpers = array('Olc', 'Media.Media');

    public function admin_q($address = '') {
        $this->autoRender = false;
        $this->response->type('json');
        $result = array(
            'queryString' => $address,
            'result' => array(),
        );
        if (!empty($address)) {
            $items = $this->Place->find('all', array(
                'conditions' => array(
                    'Place.title LIKE' => '%' . $address . '%',
                ),
                'limit' => 10,
            ));
            foreach ($items AS $k => $item) {
                $item['Place']['label'] = $item['Place']['value'] = $item['Place']['title'];
                $result['result'][] = $item['Place'];
            }
        }
        if (!isset($_GET['pretty'])) {
            $this->response->body(json_encode($result));
        } else {
            $this->response->body(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    function admin_index($typeModel = 'Door', $foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();
        if (!in_array($typeModel, array('Door', 'Land'))) {
            $typeModel = 'Door';
        }

        $foreignKeys = array(
            'Group' => 'group_id',
            'Task' => 'task_id',
        );

        $scope = array(
            'Place.model' => $typeModel,
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Place.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $this->redirect(array('controller' => 'tasks'));
            $foreignModel = '';
        }
        $foreignInfo = array();
        if (!empty($foreignModel)) {
            switch ($foreignModel) {
                case 'Task':
                    $record = $this->Place->Task->find('first', array(
                        'conditions' => array('Task.id' => $foreignId),
                    ));
                    $foreignInfo = array(
                        'title' => $record['Task']['title'],
                        'description' => $record['Task']['description'],
                    );
                    break;
            }
        }
		//搜尋條件
		$conditions=array('');
		$land_conditions=array('');
		$land_contains=array();
		$srch_title=@$this->request->data['srch_title']==""?"":$this->request->data['srch_title'];//POST
		$srch_section=@$this->request->data['srch_section']==""?"":$this->request->data['srch_section'];//POST
		$srch_code=@$this->request->data['srch_code']==""?"":$this->request->data['srch_code'];//POST
		//$conditions['title  LIKE'] = "%".$this->request->query('data.srch_title')."%";//GET
		if($srch_title!="")
		{
			$conditions['title  LIKE'] = "%".$srch_title."%";
		}
	

		if($srch_section!=""&&$srch_code!="")
		{//地段與地號必須同時填寫才能篩選
			//$land_conditions['Section.name  LIKE'] = "%".$srch_section."%";
			$land_conditions['Section.name'] = $srch_section;
			$land_contains['Section']=array('fields' => array('name'));
			$land_conditions['code'] = $srch_code;
			$land_id_a = $this->Place->Land->find('first', array(
                        'conditions' => $land_conditions,
                        'contain' => $land_contains,
                    ));
			$filter_place_id = $this->Place->PlaceLink->find('all', array(
						'conditions' => array('PlaceLink.foreign_id' => $land_id_a['Land']['id']),
						'fields' => array('PlaceLink.place_id'),
					));	
			$filter_place_id_list=array();
			foreach($filter_place_id as $key=>$val)
			{	
				$filter_place_id_list[]=$val['PlaceLink']['place_id'];
			}
			//print_r($filter_place_id_list);			
			$conditions['Place.id'] = $filter_place_id_list;			
			
		}
		
		/*
		if($srch_section!="")
		{//地段必須同時填寫才能篩選(地號選填)->負載太大,先關閉
			//$land_conditions['Section.name  LIKE'] = "%".$srch_section."%";
			$land_conditions['Section.name'] = $srch_section;
			$land_contains['Section']=array('fields' => array('name'));
			if($srch_code!="")
			{
				$land_conditions['code'] = $srch_code;
			}
			$land_id_a = $this->Place->Land->find('all', array(
                        'conditions' => $land_conditions,
                        'contain' => $land_contains,
                    ));
			$filter_place_id_list=array();		
			foreach($land_id_a as $val_land_id)
			{		
				$filter_place_id = $this->Place->PlaceLink->find('all', array(
							'conditions' => array('PlaceLink.foreign_id' => $val_land_id['Land']['id']),
							'fields' => array('PlaceLink.place_id'),
						));	
				
				foreach($filter_place_id as $val_filter_place)
				{	
					$filter_place_id_list[]=$val_filter_place['PlaceLink']['place_id'];
				}
			}
			//print_r($filter_place_id_list);			
			$conditions['Place.id'] = $filter_place_id_list;			
			
		}
		*/
		
		$this->paginate['Place']['conditions'] = $conditions;
		
        $this->set('scope', $scope);
        $this->paginate['Place']['limit'] = 40;
		$this->paginate['Place']['order'] = array('s_order' => 'ASC');
        $this->paginate['Place']['contain'] = array(
                    'Modifier' => array(
                        'fields' => array('username'),
                    ),
                    'PlaceLink'
        );
		
        $items = $this->paginate($this->Place, $scope);
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
        $this->set('foreignInfo', $foreignInfo);
        $this->set('typeModel', $typeModel);
        $this->set('groups', $this->Place->Group->find('list'));
        $this->set('tasks', $this->Place->Task->find('list'));
        $this->set('url', array($typeModel, $foreignModel, $foreignId));
		$this->set('GET_title',$srch_title);
		$this->set('GET_section',$srch_section);
		$this->set('GET_code',$srch_code);
    }

    function admin_view($id = null) {
        if (!empty($id)) {
            $item = $this->Place->find('first', array(
                'conditions' => array('Place.id' => $id),
                'contain' => array(
                    'Group' => array(
                        'fields' => array('name'),
                    ),
                    'Task' => array(
                        'fields' => array('title'),
                    ),
                    'Creator' => array(
                        'fields' => array('username'),
                    ),
                    'Modifier' => array(
                        'fields' => array('username'),
                    ),
                    'PlaceLog' => array(
                        'order' => array('PlaceLog.created' => 'DESC'),
                        'Creator' => array(
                            'fields' => array('username'),
                        ),
                    ),
                    'PlaceLink',
                ),
            ));
            if ($item['Place']['model'] === 'Land') {
                foreach ($item['PlaceLink'] AS $k => $v) {
                    $item['PlaceLink'][$k] = $this->Place->Land->find('first', array(
                        'conditions' => array('Land.id' => $v['foreign_id']),
                        'contain' => array('Section'),
                    ));
                }
            }
        }
        if (!empty($item)) {
            $this->set('item', $item);
        } else {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add($typeModel = 'Door', $foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'Group' => 'group_id',
            'Task' => 'task_id',
        );
        if (!array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $foreignModel = '';
        }
        if (empty($foreignModel)) {
            $this->redirect(array('controller' => 'tasks'));
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            if (!empty($dataToSave['PlaceLog']['file']['name'])) {
                $p = pathinfo($dataToSave['PlaceLog']['file']['name']);
                //$dataToSave['PlaceLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
				$dataToSave['PlaceLog']['file']['name'] = uniqid() . '.' . strtolower($p['extension']);
            }
            if (!empty($foreignModel)) {
                $dataToSave['Place'][$foreignKeys[$foreignModel]] = $foreignId;
            }

            $dataToSave['Place']['model'] = $typeModel;
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }

            $dataToSave['Place']['created_by'] = $dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            $this->Place->create();
            if ($this->Place->save($dataToSave)) {
                $dataToSave['PlaceLog']['place_id'] = $this->Place->getInsertID();
                $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
                $this->Place->PlaceLog->create();
                $this->Place->PlaceLog->save($dataToSave);

                if (!empty($dataToSave['PlaceLink'])) {
                    foreach ($dataToSave['PlaceLink'] AS $itemId) {
                        $this->Place->PlaceLink->create();
                        $this->Place->PlaceLink->save(array('PlaceLink' => array(
                                'place_id' => $dataToSave['PlaceLog']['place_id'],
                                'model' => $typeModel,
                                'foreign_id' => $itemId,
                        )));
                    }
                } elseif (!empty($dataToSave['Place']['foreign_id'])) {
                    $this->Place->PlaceLink->create();
                    $this->Place->PlaceLink->save(array('PlaceLink' => array(
                            'place_id' => $dataToSave['PlaceLog']['place_id'],
                            'model' => $typeModel,
                            'foreign_id' => $dataToSave['Place']['foreign_id'],
                    )));
                }

                $this->Session->setFlash('資料已經儲存');
                if (!$this->request->isAjax()) {
                    $this->redirect(array('action' => 'view', $dataToSave['PlaceLog']['place_id']));
                } else {
                    echo json_encode(array(
                        'id' => $dataToSave['Place']['id'],
                        'title' => $dataToSave['Place']['title'],
                    ));
                    exit();
                }
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('task', $this->Place->Task->read(null, $foreignId));
        $this->set('typeModel', $typeModel);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_import($typeModel = 'Door', $taskId = '') {
        if (empty($taskId)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect('/');
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('typeModel', $typeModel);
        $this->set('task', $this->Place->Task->read(null, $taskId));
    }

    function admin_edit($id = null) {
        if (!empty($id)) {
            $item = $this->Place->find('first', array(
                'conditions' => array('Place.id' => $id),
                'contain' => array('PlaceLink'),
            ));
        }
        if (!empty($item)) {
            if ($item['Place']['model'] === 'Land') {
                foreach ($item['PlaceLink'] AS $k => $v) {
                    $item['PlaceLink'][$k] = $this->Place->Land->find('first', array(
                        'conditions' => array('Land.id' => $v['foreign_id']),
                        'contain' => array('Section'),
                    ));
                }
            }
        } else {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->data)) {
            $dataToSave = $this->data;
            $dataToSave['Place']['id'] = $id;
            if (!empty($dataToSave['PlaceLog']['file']['name'])) {
                $p = pathinfo($dataToSave['PlaceLog']['file']['name']);
               // $dataToSave['PlaceLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
			   $dataToSave['PlaceLog']['file']['name'] = uniqid() . '.' . strtolower($p['extension']);
            }
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }
            $this->Place->id = $item['Place']['id'];
			if ($item['Place']['model'] === 'Land'&&isset($dataToSave['PlaceArea_Detail'])) {
			$dataToSave['Place']['area_detail'] = json_encode($dataToSave['PlaceArea_Detail'], JSON_UNESCAPED_UNICODE);
            }
			$dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            $dataToSave['Place']['modified'] = date('Y-m-d H:i:s');
			//讓日期欄位預設值為NULL,不為0000-00-00(MYSQL strict模式)
			if($dataToSave['Place']['date_begin']=="0000-00-00")
			{
				$dataToSave['Place']['date_begin']=NULL;
			}
			if($dataToSave['Place']['adopt_begin']=="0000-00-00")
			{
				$dataToSave['Place']['adopt_begin']=NULL;
			}
			if($dataToSave['Place']['adopt_end']=="0000-00-00")
			{
				$dataToSave['Place']['adopt_end']=NULL;
			}
			if($dataToSave['Place']['adopt_closed']=="0000-00-00")
			{
				$dataToSave['Place']['adopt_closed']=NULL;
			}
			if($dataToSave['PlaceLog']['date_visited']=="0000-00-00")
			{
				$dataToSave['PlaceLog']['date_visited']=NULL;
			}
			
            if ($this->Place->save($dataToSave)) {
                $this->Place->PlaceLink->deleteAll(array('place_id' => $dataToSave['Place']['id']));
                $dataToSave['PlaceLog']['place_id'] = $dataToSave['Place']['id'];
                $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
				//更新記錄
				if (!empty($id)) {
					$loaded_item = $this->Place->find('first', array(
						'conditions' => array('Place.id' => $id),
						'contain' => array('PlaceLink'),
					));
				}
				$update_log="";
				if($item['Place']['title']!=$dataToSave['Place']['title'])
				{
					$update_log="名稱：".$item['Place']['title']." -> ".$dataToSave['Place']['title']." \r\n".$update_log;
				}
				if($item['Place']['description']!=$dataToSave['Place']['description'])
				{
					$update_log="位置描述：".$item['Place']['description']." -> ".$dataToSave['Place']['description']." \r\n".$update_log;
				}
				if($item['Place']['latitude']!=$dataToSave['Place']['latitude'])
				{
					$update_log="緯度：".$item['Place']['latitude']." -> ".$dataToSave['Place']['latitude']." \r\n".$update_log;
				}
				if($item['Place']['longitude']!=$dataToSave['Place']['longitude'])
				{
					$update_log="經度：".$item['Place']['longitude']." -> ".$dataToSave['Place']['longitude']." \r\n".$update_log;
				}
				
				if($item['Place']['area']!=$dataToSave['Place']['area'])
				{
					$update_log="列管地面積：".$item['Place']['area']." -> ".$dataToSave['Place']['area']." \r\n".$update_log;
				}
				if(isset($item['Place']['adopt_area']))
				{
					if($item['Place']['adopt_area']!=$dataToSave['Place']['adopt_area'])
					{
						$update_log="領養地面積：".$item['Place']['adopt_area']." -> ".$dataToSave['Place']['adopt_area']." \r\n".$update_log;
					}
				}
				if($item['Place']['is_adopt']!=$dataToSave['Place']['is_adopt'])
				{
					$yes_or_no_s=$item['Place']['is_adopt']==1?"是":"否";
					$yes_or_no_e=$dataToSave['Place']['is_adopt']==1?"是":"否";
					$update_log="是否為認養地：".$yes_or_no_s." -> ".$yes_or_no_e." \r\n".$update_log;
				}
				if($item['Place']['adopt_type']!=$dataToSave['Place']['adopt_type'])
				{
					$update_log="認養類型：".$item['Place']['adopt_type']." -> ".$dataToSave['Place']['adopt_type']." \r\n".$update_log;
				}
				if($item['Place']['status']!=$dataToSave['Place']['status'])
				{
					$update_log="狀態：".$item['Place']['status']." -> ".$dataToSave['Place']['status']." \r\n".$update_log;
				}
				if($item['Place']['issue']!=$dataToSave['Place']['issue'])
				{
					$update_log="待改善情形：".$item['Place']['issue']." -> ".$dataToSave['Place']['issue']." \r\n".$update_log;
				}
				if($item['Place']['inspect']!=$dataToSave['Place']['inspect'])
				{
					$update_log="稽查單位：".$item['Place']['inspect']." -> ".$dataToSave['Place']['inspect']." \r\n".$update_log;
				}
				if($item['Place']['ownership']!=$dataToSave['Place']['ownership'])
				{
					$update_log="土地/房屋權屬：".$item['Place']['ownership']." -> ".$dataToSave['Place']['ownership']." \r\n".$update_log;
				}
				if($item['Place']['owner']!=$dataToSave['Place']['owner'])
				{
					$update_log="土地/房屋所有權人：".$item['Place']['owner']." -> ".$dataToSave['Place']['owner']." \r\n".$update_log;
				}
				if($item['Place']['date_begin']!=$dataToSave['Place']['date_begin'])
				{
					$update_log="開始列管日期：".$item['Place']['date_begin']." -> ".$dataToSave['Place']['date_begin']." \r\n".$update_log;
				}	
				if($item['Place']['is_rule_area']!=$dataToSave['Place']['is_rule_area'])
				{
					$yes_or_no_s=$item['Place']['is_rule_area']==1?"是":"否";
					$yes_or_no_e=$dataToSave['Place']['is_rule_area']==1?"是":"否";
					$update_log="是否位於空地空屋管理自治條例公告實施範圍：".$yes_or_no_s." -> ".$yes_or_no_e." \r\n".$update_log;
				}
				if($item['Place']['adopt_begin']!=$dataToSave['Place']['adopt_begin'])
				{
					$update_log="認養契約簽訂起始日：".$item['Place']['adopt_begin']." -> ".$dataToSave['Place']['adopt_begin']." \r\n".$update_log;
				}
				if($item['Place']['adopt_end']!=$dataToSave['Place']['adopt_end'])
				{
					$update_log="認養契約期限：".$item['Place']['adopt_end']." -> ".$dataToSave['Place']['adopt_end']." \r\n".$update_log;
				}			
				if($item['Place']['adopt_closed']!=$dataToSave['Place']['adopt_closed'])
				{
					$update_log="解除認養日期：".$item['Place']['adopt_closed']." -> ".$dataToSave['Place']['adopt_closed']." \r\n".$update_log;
				}
				if($item['Place']['adopt_by']!=$dataToSave['Place']['adopt_by'])
				{
					$update_log="認養維護單位：".$item['Place']['adopt_by']." -> ".$dataToSave['Place']['adopt_by']." \r\n".$update_log;
				}
				$dataToSave['PlaceLog']['update_log']=$update_log;
                $this->Place->PlaceLog->create();
                $this->Place->PlaceLog->save($dataToSave);

                if (!empty($dataToSave['PlaceLink'])) {
                    foreach ($dataToSave['PlaceLink'] AS $itemId) {
                        $this->Place->PlaceLink->create();
                        $this->Place->PlaceLink->save(array('PlaceLink' => array(
                                'place_id' => $dataToSave['PlaceLog']['place_id'],
                                'model' => $item['Place']['model'],
                                'foreign_id' => $itemId,
                        )));
                    }
                } elseif (!empty($dataToSave['Place']['foreign_id'])) {
                    $this->Place->PlaceLink->create();
                    $this->Place->PlaceLink->save(array('PlaceLink' => array(
                            'place_id' => $dataToSave['PlaceLog']['place_id'],
                            'model' => $item['Place']['model'],
                            'foreign_id' => $dataToSave['Place']['foreign_id'],
                    )));
                }

                /*
                 * close related tracker
                 */
                $this->Place->Tracker->updateAll(array(
                    'completed' => 'now()',
                    'completed_by' => $this->loginMember['id'],
                        ), array(
                    'Tracker.completed IS NULL',
                    'Tracker.place_id' => $dataToSave['PlaceLog']['place_id'],
                ));
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('task', $this->Place->Task->read(null, $item['Place']['task_id']));
        $this->set('id', $id);
        $this->data = $item;
    }

    function admin_delete($id = null) {
        if (!empty($id)) {
            if ($this->Place->delete($id)) {
                $this->Session->setFlash('資料已經刪除');
            } else {
                $this->Session->setFlash('請依照網址指示操作');
            }
        } else {
            $this->Session->setFlash('請依照網址指示操作');
        }
        //$this->redirect(array('action' => 'index'));
		$this->redirect(array('action' => 'index', 'Land', 'Task', $this->request->query('taskId')));
    }

    public function admin_import_door($taskId = '') {
        if (empty($taskId)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect('/');
        }
        if (!empty($this->data['Place']['file']['size'])) {
            $lastLine = array();
            $fh = fopen($this->data['Place']['file']['tmp_name'], 'r');
            /*
             * Array
              (
              [0] => 編號
              [1] => 區別
              [2] => 里別
              [3] => 座落地點
              [4] => 地段
              [5] => 地號
              [6] => 空地面積(m²)
              [7] => 土地權屬(國有/市有/私有)
              [8] => 土地管理機關土地所有權人
              [9] => 開始列管日期
              [10] => 解除列管日期
              [11] => 是否位於空地空屋管理自治條例公告實施範圍
              [12] => 是否認養
              [13] => 設置類別
              [14] => 認養契約簽訂起始日
              [15] => 契約期限
              [16] => 解除認養日期
              [17] => 認養維護單位
              [18] => 備註(如附現地照片)
              )
             */
            $result = array();
            $placeCounter = 0;
            while ($line = fgetcsv($fh, 2048)) {
                if (count($line) === 14 && is_numeric($line[5])) {
					if($line[1]!=""&&$line[2]!=""&&$line[3]!="")
					{//必須前幾列有填,才能作為後面列參考範例
						$lastLine = $line;
					}
                    foreach ($line AS $k => $v) {
                        $line[$k] = trim(str_replace("\n", ' ', $v));
                        if (empty($line[$k]) && isset($lastLine[$k])) {
                            $line[$k] = $lastLine[$k];
                        }
                    }
                    if (!isset($result[$line[0]])) {
                        $result[$line[0]] = array();
                    }
                    $result[$line[0]][] = $line;
                }
            }
            foreach ($result AS $lands) {
                $dataToSave = array('Place' => array(
                        'model' => 'Door',
                        'task_id' => $taskId,
                        'title' => $lands[0][3],
                        'status' => '1',
                        'area' => $lands[0][6],
                        'ownership' => $lands[0][7],
                        'owner' => $lands[0][8],
                        'date_begin' => $this->parseDate($lands[0][9]),
                        'is_rule_area' => ($lands[0][11] === '是') ? true : false,
                        'note' => $lands[0][12],
                        'created_by' => $this->loginMember['id'],
                        'modified_by' => $this->loginMember['id'],
                ));
                foreach ($lands AS $land) {
                    $dataToSave['Place']['note'] .= "\n地號： {$land[4]}{$land[5]}";
                }
                if (!empty($this->data['Place']['group_id']) && $this->loginMember['group_id'] == 1) {
                    $dataToSave['Place']['group_id'] = $this->data['Place']['group_id'];
                } else {
                    $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
                }
                $doors = $this->Place->Door->queryKeyword("{$lands[0][1]}區{$lands[0][2]}里{$lands[0][3]}號");
                if (count($doors['result']) === 1) {
                    $dataToSave['Place']['longitude'] = $doors['result'][0]['longitude'];
                    $dataToSave['Place']['latitude'] = $doors['result'][0]['latitude'];
                }

                $this->Place->create();
                if ($this->Place->save($dataToSave)) {
                    ++$placeCounter;
                    $dataToSave['PlaceLog']['place_id'] = $this->Place->getInsertID();
                    $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                    $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
                    $this->Place->PlaceLog->create();
                    $this->Place->PlaceLog->save($dataToSave);

                    $lands[0][3] = substr($lands[0][3], 0, strpos($lands[0][3], '號'));
                    
                    if (count($doors['result']) === 1) {
                        $this->Place->PlaceLink->create();
                        $this->Place->PlaceLink->save(array('PlaceLink' => array(
                                'place_id' => $dataToSave['PlaceLog']['place_id'],
                                'model' => 'Land',
                                'foreign_id' => $doors['result'][0]['id'],
                        )));
                    }
                }
            }
            $this->Session->setFlash("匯入了 {$placeCounter} 筆資料");
            $this->redirect(array('action' => 'index', 'Door', 'Task', $taskId));
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('taskId', $taskId);
        $this->set('task', $this->Place->Task->read(null, $taskId));
    }

    public function admin_import_land($taskId = '') {
		$import_msg="";
        if (empty($taskId)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect('/');
        }
        if (!empty($this->data['Place']['file']['size'])) {
            $lastLine = array();
            $fh = fopen($this->data['Place']['file']['tmp_name'], 'r');
            /*
             * Array
              (
              [0] => 編號
              [1] => 區別
              [2] => 里別
              [3] => 座落地點
              [4] => 地段
              [5] => 地號
              [6] => 空地面積(m²)
              [7] => 土地權屬(國有/市有/私有)
              [8] => 土地管理機關土地所有權人
              [9] => 開始列管日期
              [10] => 解除列管日期
              [11] => 是否位於空地空屋管理自治條例公告實施範圍
              [12] => 是否認養
              [13] => 設置類別
              [14] => 認養契約簽訂起始日
              [15] => 契約期限
              [16] => 解除認養日期
              [17] => 認養維護單位
              [18] => 備註(如附現地照片)
              )
             */
            $result = array();
            $placeCounter = 0;
            while ($line = fgetcsv($fh, 2048)) {
                if (count($line) >= 19 && count($line) <= 30 &&is_numeric($line[5])) {
					if($line[1]!=""&&$line[6]!="")
					{//必須前幾欄(地區,面積)有填,才能作為後面列的參考範例(適用於同一空地多列地號情況)
						$lastLine = $line;
					}
                    foreach ($line AS $k => $v) {
                        $line[$k] = trim(str_replace("\n", ' ', $v));
                        if (empty($line[$k]) && isset($lastLine[$k])) {
                            $line[$k] = $lastLine[$k];
                        }
                    }
                    if (!isset($result[$line[0]])) {
                        $result[$line[0]] = array();
                    }
                    $result[$line[0]][] = $line;
					
                }
            }
			
            foreach ($result AS $lands) {
                $dataToSave = array('Place' => array(
                        'model' => 'Land',
                        'task_id' => $taskId,
                        'title' => $lands[0][3],
                        'status' => '1',
                        'is_adopt' => ($lands[0][12] === '是') ? '1' : '0',
                        'adopt_type' => $lands[0][13],
                        'area' => $lands[0][6],
                        'ownership' => $lands[0][7],
                        'owner' => $lands[0][8],
                        'date_begin' => $this->parseDate($lands[0][9]),
                        'is_rule_area' => ($lands[0][11] === '是') ? true : false,
                        'adopt_begin' => $this->parseDate($lands[0][14]),
                        'adopt_end' => $this->parseDate($lands[0][15]),
                        'adopt_closed' => $this->parseDate($lands[0][16]),
                        'adopt_by' => $lands[0][17],
                        'note' => $lands[0][18],
                        'created_by' => $this->loginMember['id'],
                        'modified_by' => $this->loginMember['id'],
						's_order' => $lands[0][0],
                ));
				
				
                if (!empty($this->data['Place']['group_id']) && $this->loginMember['group_id'] == 1) {
                    $dataToSave['Place']['group_id'] = $this->data['Place']['group_id'];
                } else {
                    $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
                }
                $this->Place->create();
                if ($this->Place->save($dataToSave)) {
                    ++$placeCounter;
                    $dataToSave['PlaceLog']['place_id'] = $this->Place->getInsertID();
                    $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                    $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
                    $this->Place->PlaceLog->create();
                    $this->Place->PlaceLog->save($dataToSave);
					$import_msg_code="";
					for($i=0;$i<count($lands);$i++) {
						$land_key=$i;
						$land_keyword="";//格式：[中西]保安段00140000 ([區名]地段地號)
						$land_keyword_section="";						
						if($lands[$land_key][1]!="")
						{
							if(strlen($lands[$land_key][1])==9)
							{//字串調整 EX:中西區->中西 , 北區->北區(不變)
								$land_keyword_section=str_replace("區",'',$lands[$land_key][1]);
							}
							else
							{
								$land_keyword_section=$lands[$land_key][1];
							}
							
							$land_keyword.="[".$land_keyword_section."]";
						}
						//因excel吃前0關係,所以幫地號補0至8位數,以免無法對照：380000->00380000
						for($j=strlen($lands[$land_key][5]);$j<8;$j++)
						{
							$lands[$land_key][5]="0".$lands[$land_key][5];
						}
						if(substr($lands[$land_key][4],-3,3)=="段")
						{
							$land_keyword.=$lands[$land_key][4].$lands[$land_key][5];
						}
						else
						{
							$land_keyword.=$lands[$land_key][4]."段".$lands[$land_key][5];
						}
						$import_msg_code.=$lands[$land_key][5].",";
						//測試網址：.../house/lands/q/xxx
                        $lands_srch = $this->Place->Land->queryKeyword($land_keyword);
                        if (count($lands_srch['result']) === 1) {
                            $this->Place->PlaceLink->create();
                            $this->Place->PlaceLink->save(array('PlaceLink' => array(
                                    'place_id' => $dataToSave['PlaceLog']['place_id'],
                                    'model' => 'Land',
                                    'foreign_id' => $lands_srch['result'][0]['id'],
                            )));
                        }
                    }
					$import_msg.="<span style='color:#009778;font-size:10px'>第".$placeCounter."筆 ".$lands[$land_key][3]." 含地號：".$import_msg_code." -匯入成功</span><br>";
                }
				else{
					$import_msg.="<span style='color:#ff0000;'>第".$placeCounter."筆 ".$lands[$land_key][3]." -匯入失敗</span><br>";
				}
				
            }
            $this->Session->setFlash("共".count($result)."筆資料 匯入了 {$placeCounter} 筆資料<br>".$import_msg);
            $this->redirect(array('action' => 'index', 'Land', 'Task', $taskId));
        }
        if ($this->loginMember['group_id'] == 1) {
            $this->set('groups', $this->Place->Group->find('list'));
        }
        $this->set('taskId', $taskId);
        $this->set('task', $this->Place->Task->read(null, $taskId));
    }
	
	public function admin_delete_place_batch($taskId = '') {
		$this->autoRender = false;
        //$this->response->body(print_r($this->request->data['check_place_id']));
		if (isset($this->request->data['check_place_id'])) {
			if (is_array($this->request->data['check_place_id'])) {
				foreach($this->request->data['check_place_id'] as $place_id)
				{
					$this->Place->delete($place_id);
				}
			}
        } else {
            $this->Session->setFlash('請勾選刪除項目');
        }

		$this->redirect(array('action' => 'index', 'Land', 'Task', $taskId));
        
    }
	
	public function admin_update_placegeo_batch($taskId = '') {
		$this->autoRender = false;
		
		$count=0;
		$limit=20;
		$showmsg="自動轉換座標開始，一次至少轉換成功 ".$limit." 筆<hr>";
		
		if (!empty($taskId)) {
			
            $items = $this->Place->find('all', array(
				'conditions' => array('task_id' => $taskId,'latitude'=>NULL),
                'contain' => array('PlaceLink'),
				//'limit' =>$limit,
            ));

        }
		//$showmsg.=print_r($items);
		if (!empty($items)) 
		{
			foreach ($items as  $item) 
			{
			
				if (!empty($item)) 
				{
					
					$bounds  = new LatLngBounds();			
					if ($item['Place']['model'] === 'Land') {
						foreach ($item['PlaceLink'] AS $k => $v) {
							$item['PlaceLink'][$k] = $this->Place->Land->find('first', array(
								'conditions' => array('Land.id' => $v['foreign_id']),
							));
						}
					}
					//$showmsg.=print_R($item['PlaceLink']);
					if (!empty($item['PlaceLink'])&&$count<=$limit) 
					{	
						
						foreach($item['PlaceLink'] as $val)
						{
							//$showmsg.=$val['Land']['file'];
							
							$filename = ROOT .DS. 'house/webroot/json/'.$val['Land']['file'];
							
							//判斷是否有該檔案
							if(file_exists($filename))
							{
								$filejson = "";
								$file = fopen($filename, "r");
								if($file != NULL){
									//當檔案未執行到最後一筆，迴圈繼續執行(fgets一次抓一行)
									while (!feof($file)) {
										$filejson .= fgets($file);
									}
									fclose($file);
								}
								$filejson_a = json_decode($filejson,true);
								if(!empty($filejson_a['features']))
								{
									foreach($filejson_a['features'] as $index => $json_val)
									{
										if ($val['Land']['code'] == $json_val['properties']['AA49']) {
											foreach($json_val['geometry']['coordinates'] as $coordinates) {
												foreach ($coordinates as $latlng) {
													$bounds->extend(new LatLng($latlng[1],$latlng[0]));
													
												}
											}			
										}
									}
								}
							}	
						}
						$dataToSave = $this->data;
						$dataToSave['Place']['id'] = $item['Place']['id'];
						$dataToSave['Place']['latitude'] = $bounds->getCenter()->getLat();
						$dataToSave['Place']['longitude'] = $bounds->getCenter()->getLng();
						$dataToSave['Place']['modified'] = date('Y-m-d H:i:s');
						if($bounds->getCenter()->getLat()!="0.00000000"&&$bounds->getCenter()->getLng()!="180.00000000")
						{
							$this->Place->save($dataToSave);
							$showmsg.=$item['Place']['title']." 轉換完成！<br>";
							$count++;
						}
						else
						{
							//$showmsg.=$item['Place']['title']." 轉換失敗，因為沒有對應地號！<br>";
						}
					}
					else
					{
						//$showmsg.=$item['Place']['title']." 轉換失敗，因為沒有對應地號！<br>";
					}
				} 

			}//end foreach items
		}//end empty items
        else {
			$this->Session->setFlash('請依照網址指示操作');
			$this->redirect($this->referer());
		}
		if($count==0)
		{
			$showmsg="全數轉換完成，剩下資料皆為沒有對應地號";
		}
		$this->response->body($showmsg);
		
    }
	
	
    private function parseDate($s) {
        $s = trim($s);
        if (empty($s)) {
            return '';
        }
        if (strlen($s) === 7) {
            $parts = array(
                substr($s, 0, 3),
                substr($s, 3, 2),
                substr($s, 5, 2),
            );
        } else {
            $parts = preg_split('/[^0-9]+/', $s);
        }
        if ($parts[0] != 0 && $parts[0] < 1911) {
            $parts[0] += 1911;
        }

        switch (count($parts)) {
            case 3:
                return implode('-', array(
                    $parts[0],
                    str_pad($parts[1], 2, '0', STR_PAD_LEFT),
                    str_pad($parts[2], 2, '0', STR_PAD_LEFT),
                ));
                break;
            case 2:
                return implode('-', array(
                    $parts[0],
                    str_pad($parts[1], 2, '0', STR_PAD_LEFT),
                    '01',
                ));
                break;
            default:
                return '';
        }
    }

}
