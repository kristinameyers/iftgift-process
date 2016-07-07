<?php
include_once("../connect/connect.php");
include_once("../config/config.php");
if(isset($_GET['achid'])) {
unset($_SESSION['biz_withdraw_err']);
$card_id = $_GET['achid'];

$select_card_info=mysql_query("Select * from ".tbl_achnumber." where acchID='".$card_id."'");
if(mysql_num_rows($select_card_info)>0){
$row_card=mysql_fetch_object($select_card_info);
$routing_number=decrypt($row_card->routing_number); 
$ach_number=decrypt($row_card->ach_number); 	
echo $routing_number.'='.$ach_number;
}
}
?>