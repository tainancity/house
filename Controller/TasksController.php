<?php

App::uses('AppController', 'Controller');

class TasksController extends AppController {

    public $name = 'Tasks';
    public $paginate = array();
    public $helpers = array();
	
	public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('index','map');//不需登入就能使用的頁面
        }
    }
	
	function index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();


        $habtmKeys = array(
            'Group' => 'group_id',
        );
        $foreignKeys = array_merge($habtmKeys, $foreignKeys);

        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Task.' . $foreignKeys[$foreignModel]] = $foreignId;

            $joins = array(
                'Group' => array(
                    0 => array(
                        'table' => 'groups_tasks',
                        'alias' => 'GroupsTask',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.Task_id = Task.id'),
                    ),
                    1 => array(
                        'table' => 'groups',
                        'alias' => 'Group',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.Group_id = Group.id'),
                    ),
                ),
            );
            if (array_key_exists($foreignModel, $habtmKeys)) {
                unset($scope['Task.' . $foreignKeys[$foreignModel]]);
                if ($op != 'set') {
                    $scope[$joins[$foreignModel][0]['alias'] . '.' . $foreignKeys[$foreignModel]] = $foreignId;
                    $this->paginate['Task']['joins'] = $joins[$foreignModel];
                }
            }
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->paginate['Task']['limit'] = 99;
        $items = $this->paginate($this->Task, $scope);

        if ($op == 'set' && !empty($joins[$foreignModel]) && !empty($foreignModel) && !empty($foreignId) && !empty($items)) {
            foreach ($items AS $key => $item) {
                $items[$key]['option'] = $this->Task->find('count', array(
                    'joins' => $joins[$foreignModel],
                    'conditions' => array(
                        'Task.id' => $item['Task']['id'],
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
		$this -> render('index');
    }
	
    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();


        $habtmKeys = array(
            'Group' => 'group_id',
        );
        $foreignKeys = array_merge($habtmKeys, $foreignKeys);

        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Task.' . $foreignKeys[$foreignModel]] = $foreignId;

            $joins = array(
                'Group' => array(
                    0 => array(
                        'table' => 'groups_tasks',
                        'alias' => 'GroupsTask',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.Task_id = Task.id'),
                    ),
                    1 => array(
                        'table' => 'groups',
                        'alias' => 'Group',
                        'type' => 'inner',
                        'conditions' => array('GroupsTask.Group_id = Group.id'),
                    ),
                ),
            );
            if (array_key_exists($foreignModel, $habtmKeys)) {
                unset($scope['Task.' . $foreignKeys[$foreignModel]]);
                if ($op != 'set') {
                    $scope[$joins[$foreignModel][0]['alias'] . '.' . $foreignKeys[$foreignModel]] = $foreignId;
                    $this->paginate['Task']['joins'] = $joins[$foreignModel];
                }
            }
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->paginate['Task']['limit'] = 50;
        $items = $this->paginate($this->Task, $scope);

        if ($op == 'set' && !empty($joins[$foreignModel]) && !empty($foreignModel) && !empty($foreignId) && !empty($items)) {
            foreach ($items AS $key => $item) {
                $items[$key]['option'] = $this->Task->find('count', array(
                    'joins' => $joins[$foreignModel],
                    'conditions' => array(
                        'Task.id' => $item['Task']['id'],
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

    function admin_view($id = null) {
        if (!$id || !$this->data = $this->Task->read(null, $id)) {
            $this->Session->setFlash('請依照網址指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Task->create();
            if ($this->Task->save($this->data)) {
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
            if ($this->Task->save($this->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('操作發生錯誤，請重試');
            }
        }
        $this->set('id', $id);
        $this->data = $this->Task->read(null, $id);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依照網址指示操作');
        } else if ($this->Task->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }
	
	
	function map($id = null) {
        if (!empty($id)) {
            $task = $this->Task->read(null, $id);
			$places = $this->Task->Place->find('all', array(
                'conditions' => array(
                    'Place.task_id' => $id,
                ),
                'order' => array('Place.group_id' => 'ASC'),
            ));
			foreach ($places as $index=> $place) {
				$places[$index]["Place"]['owner']="(不公開)";
			}
			$this->set('places', $places);
			$this -> render('admin_map');
        }
		else {
            $this->Session->setFlash('抱歉！找不到資料');
        }
    }
	
	function admin_map($id = null) {
        if (!empty($id)) {
            $task = $this->Task->read(null, $id);
			$places = $this->Task->Place->find('all', array(
                'conditions' => array(
                    'Place.task_id' => $id,
                ),
                'order' => array('Place.group_id' => 'ASC'),
            ));
			$this->set('places', $places);
        }
    }

	
    function admin_report($id = null, $model = 'Land') {
        if (!empty($id)) {
            $task = $this->Task->read(null, $id);
        }
        if($model !== 'Land') {
            $model = 'Door';
            $label = '空屋總數';
        } else {
            $label = '空地總數';
        }
        $this->layout = 'print';
        if (!empty($task)) {
            $groups = $this->Task->Group->find('list');
            $places = $this->Task->Place->find('all', array(
                'conditions' => array(
                    'Place.task_id' => $id,
                    'Place.model' => $model,
                ),
                'fields' => array('group_id', 'status', 'is_adopt', 'adopt_type', 'area', 'adopt_area'),
                'order' => array('Place.group_id' => 'ASC'),
            ));
            $report = array();
            foreach ($places AS $place) {
                if (!isset($report[$place['Place']['group_id']])) {
					if($model !== 'Land') {
						$report[$place['Place']['group_id']] = array(
							'區別' => $groups[$place['Place']['group_id']],
							$label => 0,
							'總面積' => 0,
							'現況良好數量' => 0,
							'待改善數量' => 0,
							'認養地數量' => 0,
							'認養地面積' => 0,
							'綠美化數量' => 0,
							'綠美化面積' => 0,
							'停車場數量' => 0,
							'停車場面積' => 0,
							'運動場數量' => 0,
							'運動場面積' => 0,
							'其他公益場地數量' => 0,
							'其他公益場地面積' => 0,
						);
					 } else {
						$report[$place['Place']['group_id']] = array(
							'區別' => $groups[$place['Place']['group_id']],
							$label => 0,
							'列管地總面積' => 0,
							'現況良好數量' => 0,
							'待改善數量' => 0,
							'認養地數量' => 0,
							'認養地面積' => 0,
							'綠美化數量' => 0,
							'綠美化面積' => 0,
							'停車場數量' => 0,
							'停車場面積' => 0,
							'運動場數量' => 0,
							'運動場面積' => 0,
							'其他公益場地數量' => 0,
							'其他公益場地面積' => 0,
						);

					}
                }
                $report[$place['Place']['group_id']][$label] += 1;
				/*
				因為資料庫原有設計是varchar:修正1,735.65=>1.73565不正確轉值判斷.(資料庫原設計不動)
				參考網頁http://stackoverflow.com/questions/481466/php-string-to-float
				*/
				$place['Place']['area']=floatval(preg_replace("/[^-0-9\.]/","",$place['Place']['area']));
				$place['Place']['adopt_area']=floatval(preg_replace("/[^-0-9\.]/","",$place['Place']['adopt_area']));
				if($model !== 'Land') {
					$report[$place['Place']['group_id']]['總面積'] += $place['Place']['area'];
				} else {
					$report[$place['Place']['group_id']]['列管地總面積'] += $place['Place']['area'];
				}
                if ($place['Place']['status'] == 1) {
                    $report[$place['Place']['group_id']]['現況良好數量'] += 1;
                } else {
                    $report[$place['Place']['group_id']]['待改善數量'] += 1;
                }
                if ($place['Place']['is_adopt'] == 1) {
                    $report[$place['Place']['group_id']]['認養地數量'] += 1;
                    $report[$place['Place']['group_id']]['認養地面積'] += $place['Place']['adopt_area'];
					//認養地數量=綠美化+停車場+運動場+其他公益場地
                    switch ($place['Place']['adopt_type']) {
                        case '綠美化':
                            $report[$place['Place']['group_id']]['綠美化數量'] += 1;
                            $report[$place['Place']['group_id']]['綠美化面積'] += $place['Place']['adopt_area'];
                            break;
                        case '停車場':
                            $report[$place['Place']['group_id']]['停車場數量'] += 1;
                            $report[$place['Place']['group_id']]['停車場面積'] += $place['Place']['adopt_area'];
                            break;
                        case '運動場':
                            $report[$place['Place']['group_id']]['運動場數量'] += 1;
                            $report[$place['Place']['group_id']]['運動場面積'] += $place['Place']['adopt_area'];
                            break;
                        case '其他公益場地':
                            $report[$place['Place']['group_id']]['其他公益場地數量'] += 1;
                            $report[$place['Place']['group_id']]['其他公益場地面積'] += $place['Place']['adopt_area'];
                            break;
                    }
                }
            }
            $this->set('report', $report);
        }
    }
	
	
	function admin_report_list($id = null) {
        if (!empty($id)) {
            $task = $this->Task->read(null, $id);
        }
        $this->layout = 'excel';
        if (!empty($task)) {
            $places = $this->Task->Place->find('all', array(
                'conditions' => array(
                    'Place.task_id' => $id,
                ),
                'contain' => array('PlaceLink'),
                'order' => array('Place.group_id' => 'ASC','Place.s_order' => 'ASC'),
            ));
			$tasks = $this->Task->find('list');
            $report = array();
			$i=0;
            foreach ($places AS $place) {
				$i++;
				if($place['Place']['model'] !== 'Land') {
					$type = '空屋';
				} else {
					$type = '空地';
				}
				$is_adopt=$place['Place']['is_adopt']==1?"是":"否";
				$is_rule_area=$place['Place']['is_rule_area']==1?"是":"否";
				if($place['Place']['latitude']!=""&&$place['Place']['longitude']!="")
				{
					$lat_lng=$place['Place']['latitude'].",".$place['Place']['longitude'];
				}
				else{
					$lat_lng="";
				}
				if ($place['Place']['is_adopt'] == 1) {
					$adopt_area=$place['Place']['adopt_area'];
				}
				else{
					$adopt_area="-";
				}
				
				if ($place['Place']['model'] === 'Land') {
					if(!empty($place['PlaceLink']))
					{
						foreach ($place['PlaceLink'] AS $k => $v) {
							
							$item['PlaceLink'][$k] = $this->Task->Place->Land->find('first', array(
								'conditions' => array('Land.id' => $v['foreign_id']),
								'contain' => array('Section'),
							));
							
							if($k==0)
							{
								
								$temp_a=array(
									'編號' => $i,
									'區別' => $tasks[$place['Place']['task_id']],
									'類別' => $type,
									'座落地點' => $place['Place']['title'],
									'座標' => $lat_lng,
									'緯度' => $place['Place']['latitude'],
									'經度' => $place['Place']['longitude'],
									'狀態' => $place['Place']['status'],
									'待改善情形' => $place['Place']['issue'],
									'地段' => @$item['PlaceLink'][$k]['Section']['name'],
									'地號' => @$item['PlaceLink'][$k]['Land']['code'],
									'列管地面積(m²)' => $place['Place']['area'],
									'認養地面積(m²)' => $adopt_area,
									'土地權屬<br>(國有/市有/私有)' =>$place['Place']['ownership'],
									'土地管理機關<br>土地所有權人' => $place['Place']['owner'],
									'開始列管日期' => $place['Place']['date_begin'],
									'是否位於空地空屋管理自治條例公告實施範圍' => $is_rule_area,
									'是否認養' => $is_adopt,
									'設置類別' => $place['Place']['adopt_type'],
									'認養契約簽訂起始日' => $place['Place']['adopt_begin'],
									'契約期限' => $place['Place']['adopt_end'],
									'解除認養日期' => $place['Place']['adopt_closed'],
									'認養維護單位' => $place['Place']['adopt_by'],
									'備註' => $place['Place']['note'],
									'地段2' => '-',
									'地號2' => '-',
								);
								$report[$i] = $temp_a;
							}
							else
							{
								$t=$k+1;
								@$report[$i]['地段'.$t]=$item['PlaceLink'][$k]['Section']['name'];
								@$report[$i]['地號'.$t]=$item['PlaceLink'][$k]['Land']['code'];
								
							}
							
						}
					}
					else{
						$report[$place['Place']['id']] = array(
							'編號' => $i,
							'區別' => $tasks[$place['Place']['task_id']],
							'類別' => $type,
							'座落地點' => $place['Place']['title'],
							'座標' => $lat_lng,
							'緯度' => $place['Place']['latitude'],
							'經度' => $place['Place']['longitude'],
							'狀態' => $place['Place']['status'],
							'待改善情形' => $place['Place']['issue'],
							'地段' => '-',
							'地號' => '-',
							'列管地面積(m²)' => $place['Place']['area'],
							'認養地面積(m²)' => $adopt_area,
							'土地權屬<br>(國有/市有/私有)' =>$place['Place']['ownership'],
							'土地管理機關<br>土地所有權人' => $place['Place']['owner'],
							'開始列管日期' => $place['Place']['date_begin'],
							'是否位於空地空屋管理自治條例公告實施範圍' => $is_rule_area,
							'是否認養' => $is_adopt,
							'設置類別' => $place['Place']['adopt_type'],
							'認養契約簽訂起始日' => $place['Place']['adopt_begin'],
							'契約期限' => $place['Place']['adopt_end'],
							'解除認養日期' => $place['Place']['adopt_closed'],
							'認養維護單位' => $place['Place']['adopt_by'],
							'備註' => $place['Place']['note'],
							'地段2' => '-',
							'地號2' => '-',
						);
					}
				}
				else{
					$report[$place['Place']['id']] = array(
							'編號' => $i,
							'區別' => $tasks[$place['Place']['task_id']],
							'類別' => $type,
							'座落地點' => $place['Place']['title'],
							'座標' => $lat_lng,
							'緯度' => $place['Place']['latitude'],
							'經度' => $place['Place']['longitude'],
							'狀態' => $place['Place']['status'],
							'待改善情形' => $place['Place']['issue'],
							'地段' => '-',
							'地號' => '-',
							'列管空地面積(m²)' => $place['Place']['area'],
							'認養地面積(m²)' => $adopt_area,
							'土地權屬<br>(國有/市有/私有)' =>$place['Place']['ownership'],
							'土地管理機關<br>土地所有權人' => $place['Place']['owner'],
							'開始列管日期' => $place['Place']['date_begin'],
							'是否位於空地空屋管理自治條例公告實施範圍' => $is_rule_area,
							'是否認養' => $is_adopt,
							'設置類別' => $place['Place']['adopt_type'],
							'認養契約簽訂起始日' => $place['Place']['adopt_begin'],
							'契約期限' => $place['Place']['adopt_end'],
							'解除認養日期' => $place['Place']['adopt_closed'],
							'認養維護單位' => $place['Place']['adopt_by'],
							'備註' => $place['Place']['note'],
							'地段2' => '-',
							'地號2' => '-',
					);
				}
				//print_r($item['PlaceLink']);   
            }
            $this->set('report', $report);
        }
		$this -> render('admin_report');
    }

}
