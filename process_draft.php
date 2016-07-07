<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once ('../common/function.php');
/*echo '<pre>';
print_r($_GET);exit; */
if( isset($_GET['rid'])){
	$recpid=$_GET['rid'];
	$userId = $_SESSION['LOGINDATA']['USERID'];
	$drft=mysql_query("SELECT * From ".tbl_delivery." where delivery_id = '".$recpid."'");
	if(mysql_num_rows($drft) > 0){
	
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['proid'];
			 $pro[] = array('proid' => "$pid");	
		}	
		$json = mysql_real_escape_string(json_encode($pro));
		$Qry =mysql_query("update ".tbl_delivery." set step='step_2a',draft = '1', proid ='$json' where delivery_id = '".$recpid."'");
		if($Qry)
		{ 
			unset($_SESSION['recipit_id']['New']);
			unset($_SESSION['DRAFT']);
			unset($_SESSION['cart']);
			echo 'success';
		}
	
	}
	
}

/////////////////////////////////////////////////////////
if(isset($_GET['steps'])){
	$qry=mysql_query("SELECT * from ".tbl_delivery." Where delivery_id='".$_GET['steps']."'");
	$draft=mysql_fetch_array($qry);
	if($draft['step']){
	echo $draft['step'].'/'.base64_encode($draft['delivery_id']);
	$_SESSION['DRAFT'] = $draft; 
	}
}

if(isset($_GET['devsteps'])){
	$qry=mysql_query("SELECT * from ".tbl_delivery." Where delivery_id='".$_GET['devsteps']."'");
	$draft=mysql_fetch_array($qry);
	echo $draft['step'].'/'.base64_encode($draft['delivery_id']);
	//$_SESSION['DRAFT'] = $draft;
}

if(isset($_REQUEST['del_id']) && isset($_REQUEST['occasion']) ){
	$qry=mysql_fetch_array(mysql_query("SELECT * from ".tbl_delivery." Where delivery_id='".$_REQUEST['del_id']."'"));
	$deilvery_id=$qry['delivery_id'];
	$cash=$qry['cash_amount'];
	$fname=$qry['recp_first_name'];
	$lname=$qry['recp_last_name'];
	$email=$qry['recp_email'];
	$occas=$qry['occassionid'];
	$gender=$qry['gender'];
	$age=$qry['age'];
	$gender=$qry['gender'];
	$location=$qry['location'];
	$proid=$qry['proid'];
	$userId = $qry['userId'];
	//$occasions=$qry['occas'];
	//$occasions =$_REQUEST['occasion'];
	 
	if(isset($deilvery_id)){
		$insQry ="insert into ".tbl_delivery." set cash_amount   = '$cash',
										recp_first_name			= '$fname',
										recp_last_name 			= '$lname',
										recp_email 				= '$email',
										gender   			= '$gender',
										age      			= '$age',
										location 			= '$location',
										occassionid 			= '$occas',
										userId				= '$userId',
										proid				='$proid',
										dated 				= now()";
										//echo $insQry; exit;
					$Qry = mysql_query($insQry)or die (mysql_error());
					$_SESSION['DRAFT']['delivery_id'] = mysql_insert_id();
					if($Qry){
						echo "success";
					}
	}
}



if(isset($_REQUEST['newreg'])){
	
	$drft=mysql_query("SELECT * From ".tbl_delivery." where delivery_id = '".$_SESSION['recipit_id']['New']."'");
	if(mysql_num_rows($drft) > 0){
	
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['proid'];
			 $pro[] = array('proid' => "$pid");	
		}	
		$json = mysql_real_escape_string(json_encode($pro));
		$Qry =mysql_query("update ".tbl_delivery." set proid ='$json' where delivery_id = '".$_SESSION['recipit_id']['New']."'");
		if($Qry)
		{ 
			echo 'success';
		}
	
	}
	
}
if(isset($_REQUEST['wid'])){
	$cash=mysql_fetch_array(mysql_query("SELECT netamount,withdrawID from gift_cash_withdraw where withdrawID = '".$_REQUEST['wid']."'")); 
	$cur_cash=$cash['netamount'];
	$netamount = str_replace('$','',$cur_cash);
	$user_cash =mysql_fetch_array(mysql_query("SELECT available_cash,userId FROM ".tbl_user."  where userId='".$_SESSION['LOGINDATA']['USERID']."'"));
	$net_cash=$user_cash['available_cash'];
	$tot_cash=$net_cash + $netamount;
		if($user_cash){  
			$del_payment=$db->query("UPDATE ".tbl_user."  set available_cash='".$tot_cash."'  where userId  = '".$_SESSION['LOGINDATA']['USERID']."' "); 
			$del_cash=$db->query("delete from gift_cash_withdraw where withdrawID = '".$_REQUEST['wid']."'"); 
			if($del_cash){
				echo $netamount.'_'.$tot_cash;
			}
	} 	
}
?>