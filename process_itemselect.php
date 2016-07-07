<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");

//////////////////////////////////////REMOVE UNWRAP/////////////////////////////////////////////////

if($_GET['dId'])
{
	$pro_id = $_GET['dId'];
	$uId[] = $_GET['uId'];
	$loveId = $_GET['loveId'];
	
	$get_pro = $db->get_row("select * from ".tbl_product." where proid = '".$pro_id."'",ARRAY_A);
	$userId = explode(',',$get_pro['love_id']);
	$result = array_diff($userId, $uId);
	$love_id = implode(',',$result);
	$del = mysql_query("update ".tbl_product." set love_id = '".$love_id."' where proid = '".$pro_id."'");
	if($del)
	{
		$del_love = mysql_query("delete from ".tbl_love." where love_id = '".$loveId."'");
		echo "Success";
	}
}

if($_GET['dID'])
{
	$pro_id = $_GET['dID'];
	$uId[] = $_GET['uId'];
	$ownid = $_GET['ownid'];
	
	$get_pro = $db->get_row("select * from ".tbl_product." where proid = '".$pro_id."'",ARRAY_A);
	$userId = explode(',',$get_pro['own_id']);
	$result = array_diff($userId, $uId);
	$own_id = implode(',',$result);
	$del = mysql_query("update ".tbl_product." set own_id = '".$own_id."' where proid = '".$pro_id."'");
	if($del)
	{
		$del_love = mysql_query("delete from ".tbl_own." where own_id = '".$ownid."'");
		echo "Success";
	}
}

if($_GET['DID'])
{
	$pro_id = $_GET['DID'];
	$uId[] = $_GET['uId'];
	$hideid = $_GET['hideid'];
	
	$get_pro = $db->get_row("select * from ".tbl_product." where proid = '".$pro_id."'",ARRAY_A);
	$userId = explode(',',$get_pro['hide_id']);
	$result = array_diff($userId, $uId);
	$hide_id = implode(',',$result);
	$del = mysql_query("update ".tbl_product." set hide_id = '".$hide_id."' where proid = '".$pro_id."'");
	if($del)
	{
		$del_love = mysql_query("delete from ".tbl_hide." where hide_id = '".$hideid."'");
		echo "Success";
	}
}
?>