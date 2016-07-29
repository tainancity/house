<?php

App::uses('AppModel', 'Model');

class Door extends AppModel {

    var $name = 'Door';
    var $validate = array(
        'floor' => array(
            'numberFormat' => array(
                'rule' => 'numeric',
                'message' => 'Wrong format',
                'allowEmpty' => true,
            ),
        ),
    );

    public function queryKeyword($address) {
        $result = array(
            'queryString' => $address,
            'result' => array(),
        );
        $doors = $this->find('all', array(
            'conditions' => $this->extractAddress($address),
            'limit' => 10,
        ));
        foreach ($doors AS $k => $item) {
            $item['Door']['lin'] = intval($item['Door']['lin']);
            $item['Door']['label'] = $item['Door']['value'] = "{$item['Door']['area']}{$item['Door']['cunli']}{$item['Door']['lin']}鄰{$item['Door']['road']}{$item['Door']['place']}{$item['Door']['lane']}{$item['Door']['alley']}{$item['Door']['number']}";
            $result['result'][] = $item['Door'];
        }
        return $result;
    }

    public function extractAddress($address = '', $asCondition = true) {
        $address = preg_replace('/\s+/', '', $address);
        $address = str_replace(array('．'), '', $address);
        $numbersMap = array(
            '0' => '０', '1' => '１', '2' => '２', '3' => '３', '4' => '４', '5' => '５',
            '6' => '６', '7' => '７', '8' => '８', '9' => '９',
        );
        $address = strtr($address, $numbersMap);
        $tnKeys = array(
            '區' => array(
                'col' => 'area',
                'order' => 0,
            ),
            '里' => array(
                'col' => 'cunli',
                'order' => 0,
            ),
            '路' => array(
                'col' => 'road',
                'order' => 0,
            ),
            '街' => array(
                'col' => 'road',
                'order' => 1,
            ),
            '段' => array(
                'col' => 'road',
                'order' => 2,
            ),
            '巷' => array(
                'col' => 'lane',
                'order' => 0,
            ),
            '弄' => array(
                'col' => 'alley',
                'order' => 0,
            ),
            '號' => array(
                'col' => 'number',
                'order' => 0,
            ),
        );
        $preMatches = array(
            'area' => array(
                '新市區', '佳里區',
            ),
            'cunli' => array(
                '巷口里', '後街里', '中樓里', '路東里', '仁里里', '科里里', '後市里',
                '豐里里', '新市里',
            ),
            'place' => array(
                '蚵(壳)潭', '佳里興', '新市子', '(那)拔林', '西阿里關', '大(塭)寮',
                '(檨)子林', '八德橫巷', '市場二巷', '東山市場', '九龍橫巷', '昇平橫巷',
                '協進市場', '米市園', '中正號', '三民西巷', '康樂市場', '苗圃巷',
                '市場六巷', '中山號', '市場一巷',
            ),
            'road' => array(
                '新樓街', '仁里街', '市場北街',
            ),
            'lane' => array(
                '南一巷',
            ),
        );
        $keys = explode('|', '縣|市|區|里|鄰|路|段|街|巷|弄|號|樓');
        $pattern = '/[^' . implode('', $keys) . ']*(' . implode('|', $keys) . ')/u';

        $newItem = array();
        foreach ($preMatches AS $col => $parts) {
            foreach ($parts AS $part) {
                $pos = strpos($address, $part);
                if (false !== $pos) {
                    $newItem[$col] = array(0 => $part);
                    $address = substr($address, 0, $pos) . substr($address, $pos + strlen($part));
                }
            }
        }
        preg_match_all($pattern, $address, $matches);

        foreach ($matches[1] AS $k => $v) {
            if (isset($tnKeys[$v])) {
                if (!isset($newItem[$tnKeys[$v]['col']])) {
                    $newItem[$tnKeys[$v]['col']] = array();
                }
                if (!isset($newItem[$tnKeys[$v]['col']][$tnKeys[$v]['order']])) {
                    $newItem[$tnKeys[$v]['col']][$tnKeys[$v]['order']] = $matches[0][$k];
                }
            }
        }
        foreach ($newItem AS $k => $v) {
            ksort($v);
            $newItem[$k] = implode('', $v);
        }
        if (isset($newItem['lin'])) {
            $newItem['lin'] = str_pad(intval($newItem['lin']), 3, '0', STR_PAD_LEFT);
        }
        $placeKeys = array('number', 'lane', 'alley');
        foreach ($placeKeys AS $placeKey) {
            if (isset($newItem[$placeKey])) {
                $numberMapKey = implode('', $numbersMap);
                preg_match('/[' . $numberMapKey . ']+/u', $newItem[$placeKey], $matches, PREG_OFFSET_CAPTURE);
                if (isset($matches[0][1]) && $matches[0][1] !== 0) {
                    $newItem['place'] = substr($newItem[$placeKey], 0, $matches[0][1]);
                    $newItem[$placeKey] = substr($newItem[$placeKey], $matches[0][1]);
                }
            }
        }
        if (isset($newItem['lane']) && isset($newItem['cunli']) && $newItem['cunli'] === '仁和里' && in_array($newItem['lane'], array('仁巷', '和巷'))) {
            $newItem['place'] = $newItem['lane'];
            unset($newItem['lane']);
        }
        if (isset($newItem['place'])) {
            switch ($newItem['place']) {
                case '東':
                    $newItem['number'] = '東' . $newItem['number'];
                    unset($newItem['place']);
                    break;
                case '﹒':
                    unset($newItem['place']);
                    break;
                default:
                    if (false !== strpos($newItem['place'], '地下')) {
                        unset($newItem['place']);
                    }
            }
        }
        if ($asCondition) {
            if (isset($newItem['road'])) {
                $newItem['road LIKE'] = $newItem['road'] . '%';
                unset($newItem['road']);
            }
            if (isset($newItem['number'])) {
                $newItem['number LIKE'] = $newItem['number'] . '%';
                unset($newItem['number']);
            }
        }
        return $newItem;
    }

}
