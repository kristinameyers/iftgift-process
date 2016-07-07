<?php
include_once("../connect/connect.php");
include_once("../config/config.php");
if(isset($_GET['card_id'])) {
unset($_SESSION['biz_deposit_err']);
$card_id = $_GET['card_id'];

$select_card_info=mysql_query("Select * from ".tbl_member_card." where memberID='".$card_id."'");
if(mysql_num_rows($select_card_info)>0){
$row_card=mysql_fetch_object($select_card_info);

$card_number=decrypt($row_card->card_number); 	
$card_pin=decrypt($row_card->pin);
$expiry_month=decrypt($row_card->expiry_month);
$expiry_year=decrypt($row_card->expiry_year);
$card_type=decrypt($row_card->card_type);
$fname=$row_card->fname;
$lname=$row_card->lname;
$address1=$row_card->address1;
$address2=$row_card->address2;
$state=$row_card->state;
$city=$row_card->city;
$zip=$row_card->zip;
$ip=$row_card->ip;
$payment_method=$row_card->payment_method;
echo $card_number.'='.$card_pin.'='.$expiry_month.'='.$expiry_year.'='.$card_type.'='.$fname.'='.$lname.'='.$address1.'='.$address2.'='.$state.'='.$city.'='.$zip.'='.$ip.'='.$payment_method.'='.$card_id;
}
}
?>