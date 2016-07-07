<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once ('../common/function.php');

//////////////////////////////////////REMOVE UNWRAP/////////////////////////////////////////////////

if($_GET['dId'])
{
	$delivery_id = $_GET['dId'];
	
	//$del = mysql_query("delete from ".tbl_delivery." where delivery_id = '".$delivery_id."'");
	$del = mysql_query("update ".tbl_delivery." set inbox = '1', unwrap_status = '0' where delivery_id = '".$delivery_id."'");
	if($del)
	{
		echo "Success";
	}
}

if($_GET['opendel'])
{
	$delivery_id = $_GET['opendel'];
	
	//$del = mysql_query("delete from ".tbl_delivery." where delivery_id = '".$delivery_id."'");
	$del = mysql_query("update ".tbl_delivery." set inbox = '1', open_status = '0' where delivery_id = '".$delivery_id."'");
	if($del)
	{
		echo "Success";
	}
}

if($_GET['pId'] && $_GET['time_dev'] && $_GET['cash_amount'] && $_GET['tcash'])
{
	
	$delivery_id = $_GET['pId'];
	$time_unwrapped = $_GET['time_dev'];
	$cash_amount = $_GET['cash_amount'];
	$total_cash = $_GET['tcash'];
	
	$Qry = mysql_query("update ".tbl_delivery." set open_status = '0', unwrap_status = '3', unwrap_date = '".$time_unwrapped."' where delivery_id = '".$delivery_id."'");
	if($Qry)
	{
		$new_cash = $cash_amount + $total_cash;
		$Qry1 = mysql_query("update ".tbl_user." set available_cash = '".$new_cash."' where userId = '".$_SESSION['LOGINDATA']['USERID']."'");
		echo "Success";
	}
}


if($_GET['open'] && $_GET['time_dev'])
{

	$delivery_id = $_GET['open'];
	$time_unwrapped = $_GET['time_dev'];
	$get_query = mysql_query("select unwrap_date from ".tbl_delivery." where delivery_id = '".$delivery_id."'");
	$get_unwrapdate = mysql_fetch_array($get_query);
	if($get_unwrapdate['unwrap_date'] != '')
	{
		echo "Success";
	} else {	
	
	$Qry = mysql_query("update ".tbl_delivery." set unwrap_date = '".$time_unwrapped."' where delivery_id = '".$delivery_id."'");
	if($Qry)
	{
		echo "Success";
	}
}
}


if($_GET['gId'])
{
	 $delivery_id = $_GET['gId'];	
	 $Qry = mysql_query("update ".tbl_delivery." set game_flag = '0' where delivery_id = '".$delivery_id."'");
	if($Qry)
	{
		echo "Success";
	}
}

?>
