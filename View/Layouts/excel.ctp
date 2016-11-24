<?php
header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
header('Content-Disposition: attachment; filename=excel'.date('Y-m-d').'.xls');  //設定檔案名稱
echo $content_for_layout; 
?>
