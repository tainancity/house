<?php

App::uses('AppController', 'Controller');

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
		$conditions['title  LIKE'] = "%".$this->request->query('data.srch_title')."%";
		$this->paginate['Place']['conditions'] = $conditions;
		
        $this->set('scope', $scope);
        $this->paginate['Place']['limit'] = 20;
        $this->paginate['Place']['contain'] = array(
            'Modifier' => array(
                'fields' => array('username'),
            ),
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
		$this->set('GET_title',$this->request->query('data.srch_title'));
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
                $dataToSave['PlaceLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
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
                $dataToSave['PlaceLog']['file']['name'] = uuid_create() . '.' . strtolower($p['extension']);
            }
            if ($this->loginMember['group_id'] != 1) {
                $dataToSave['Place']['group_id'] = $this->loginMember['group_id'];
            }
            $this->Place->id = $item['Place']['id'];
            $dataToSave['Place']['modified_by'] = $this->loginMember['id'];
            $dataToSave['Place']['modified'] = date('Y-m-d H:i:s');
            if ($this->Place->save($dataToSave)) {
                $this->Place->PlaceLink->deleteAll(array('place_id' => $dataToSave['Place']['id']));
                $dataToSave['PlaceLog']['place_id'] = $dataToSave['Place']['id'];
                $dataToSave['PlaceLog']['status'] = $dataToSave['Place']['status'];
                $dataToSave['PlaceLog']['created_by'] = $this->loginMember['id'];
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
                    $lastLine = $line;
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
                if (count($line) === 19 && is_numeric($line[5])) {
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
                    $lastLine = $line;
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
						if(@$lands[$land_key][1]!="")
						{
							if(strlen($lands[$land_key][1])==9)
							{//中西區->中西 , 北區->北區(不變)
								@$land_keyword_section=str_replace("區",'',$lands[$land_key][1]);
							}
							
							$land_keyword.="[".$land_keyword_section."]";
						}
						//因excel吃前0關係,所以幫地號補0至8位數,以免無法對照：380000->00380000
						for($j=strlen($lands[$land_key][5]);$j<8;$j++)
						{
							$lands[$land_key][5]="0".$lands[$land_key][5];
						}
						@$land_keyword.=str_replace("段",'',$lands[$land_key][4])."段".$lands[$land_key][5];
						@$import_msg_code.=$lands[$land_key][5].",";
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
					$import_msg.="<span style='color:#009778;font-size:10px'>第".$placeCounter."筆 ".$lands[$land_key][3]."含 地號：".$import_msg_code." -匯入成功</span><br>";
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
