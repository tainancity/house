<?php

class OlcHelper extends AppHelper {

    public $helpers = array('Html');
    public $status = array(
        1 => '現況良好',
        2 => '待改善',
    );
    public $issue = array(
        '' => '--',
        '雜草過高' => '雜草過高',
        '環境髒亂' => '環境髒亂',
    );
    public $adopt_types = array(
        '' => '--',
        '綠美化' => '綠美化',
        '停車場' => '停車場',
        '運動場' => '運動場',
        '其他公益場地' => '其他公益場地',
    );

}
