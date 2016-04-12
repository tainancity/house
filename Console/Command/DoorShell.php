<?php

class DoorShell extends AppShell {

    public $uses = array('Door');

    public function main() {
        
    }

    public function dbTesting() {
        $items = $this->Door->find('all');
        foreach ($items AS $item) {
            $item['Door']['lin'] = intval($item['Door']['lin']) . '鄰';
            $combined = "台南市{$item['Door']['area']}{$item['Door']['cunli']}{$item['Door']['lin']}{$item['Door']['road']}{$item['Door']['place']}{$item['Door']['lane']}{$item['Door']['alley']}{$item['Door']['number']}";
            $newItem = $this->Door->extractAddress($combined);
            $itemMatched = true;
            foreach ($newItem AS $k => $v) {
                switch ($k) {
                    case 'number':
                    case 'road':
                        if (substr($item['Door'][$k], 0, strlen($v)) !== $v) {
                            $itemMatched = false;
                        }
                        break;
                    default:
                        if ($item['Door'][$k] !== $v) {
                            $itemMatched = false;
                        }
                }
            }
            if (false === $itemMatched) {
                print_r($item['Door']);
                print_r($newItem);
            }
        }
    }

}
