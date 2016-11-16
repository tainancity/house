<?php

class Land extends AppModel {

    public $name = 'Land';
    var $belongsTo = array(
        'Section' => array(
            'foreignKey' => 'section_id',
            'className' => 'Section',
        ),
    );

    public function queryKeyword($address) {
        $result = array(
            'queryString' => $address,
            'result' => array(),
        );
        $address = preg_replace('/\s+/', '', $address);//address標準輸入格式為[中西]保安段00140000
        if (!empty($address)) {
            $pos = strrpos($address, '段');
            if (false === $pos) {
                $sectionPart = $address;
            } else {
                $sectionPart = substr($address, 0, $pos);
            }

            if (!empty($sectionPart)) {
                $conditions = array('OR' => array(
                        'Section.name LIKE' => '%' . $sectionPart . '%'
                ));
                $sectionPartClean = preg_replace('/[^a-z0-9]/i', '', $sectionPart);
                if (!empty($sectionPartClean)) {
                    $conditions['OR']['Section.id LIKE'] = '%' . $sectionPartClean . '%';
                }
                $sections = $this->Section->find('all', array(
                    'conditions' => $conditions,
                    'limit' => 10,
                ));
                
				$landPart = substr($address, $pos);
                $numbersMap = array('０' => 0, '１' => 1, '２' => 2, '３' => 3, '４' => 4, '５' => 5, '６' => 6, '７' => 7, '８' => 8, '９' => 9,);
                $landPart = strtr($landPart, $numbersMap);
				$landPart = preg_replace('/[^0-9\\-]/', '', $landPart);
	
                if (count($sections) === 1 && !empty($landPart)) {
                    $conditions = array(
                        'Land.section_id' => $sections[0]['Section']['id'],
                    );
					
                    $pos = strpos($landPart, '-');
                    if (false !== $pos) {
                        $landPart = str_pad(substr($landPart, 0, $pos), 4, '0', STR_PAD_LEFT) . str_pad(substr($landPart, $pos + 1), 4, '0', STR_PAD_LEFT);
                    }
                    $landPartLength = strlen($landPart);
                    if ($landPartLength <= 4) {
                        $landPart = '%' . $landPart . '%';
                    } elseif ($landPartLength < 8) {
                        //$landPart .= '%';
						$landPart = '%' . $landPart . '%';//防治地號前面有0,excel讀不出
                    } elseif ($landPartLength > 8) {
                        $landPart = substr($landPart, 0, 8);
                    }
                    if (false !== strpos($landPart, '%')) {
                        $conditions['Land.code LIKE'] = $landPart;
                    } else {
                        $conditions['Land.code'] = $landPart;
                    }
					
          
                    $lands = $this->find('all', array(
                        'conditions' => $conditions,
                        'limit' => 200
                    ));
                    foreach ($lands AS $k => $item) {
                        $item['Land']['label'] = $item['Land']['value'] = "{$sections[0]['Section']['name']}{$item['Land']['code']}";
                        $result['result'][] = $item['Land'];
                    }
                } else {
                    foreach ($sections AS $k => $item) {
                        $item['Section']['label'] = $item['Section']['value'] = $item['Section']['name'];
                        $result['result'][] = $item['Section'];
                    }
                }
            }
        }
        return $result;
    }

}
