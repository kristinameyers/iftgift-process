<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");

$userId = $_POST['userid'];
$party_mode = $_POST['mode'];

$update_mode = mysql_query("update ".tbl_user." set party_mode = '".$party_mode."' where userid = '".$userId."'");
if($update_mode)
{
	$get_mod = mysql_fetch_array(mysql_query("select party_mode from ".tbl_user." where userId = '".$userId."'"));
	if($get_mod)
	{
		echo $get_mod['party_mode'];
		
	}
}

?>