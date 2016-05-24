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
        '廢棄物' => '廢棄物',
        '積水容器' => '積水容器',
    );

}
