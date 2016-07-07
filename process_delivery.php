<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once ('../common/function.php');
//require '../phpmailer/class.smtp.php';
//require '../phpmailer/class.phpmailer.php';
unset($_SESSION['biz_giv_err']);
unset($_SESSION['biz_giv']);
/*echo "<pre>";
print_r($_POST); exit;*/


function get_images($image)
{
	$img =  preg_replace("/<a[^>]+\>/i", "", $image);
	preg_match("/src=([^>\\']+)/", $img, $result);
	$view_image = array_pop($result);
	return $view_image;
}
$server=date('Y-m-d h:i:s');
if(isset($_POST['delivery_detail'])) {
	foreach ($_POST as $k => $v ){
		$$k =  $v;
		$_SESSION['dev_detail'][$k]=$v;
	}
	

	if($recp_first_name == '') {
		$_SESSION['dev_detail_err']['recp_first_name'] = 'Please enter First Name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if(!$recp_email){
		$_SESSION['dev_detail_err']['recp_email'] = $_ERR['register']['recp_email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}elseif($recp_email!=''){
		if (vpemail($recp_email )){
			$_SESSION['dev_detail_err']['recp_email'] = $_ERR['register']['recp_email'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}else{
			list($username,$domain)=explode('@',$recp_email);
				if(checkdnsrr($domain,'MX')) {
				}else{
					$_SESSION['dev_detail_err']['recp_email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
					header('location:'.ru.'delivery_detail'); exit;
			}
		}
	}
	
	if($snd_first_name == '') {
		$_SESSION['dev_detail_err']['snd_first_name'] = 'Please enter First Name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if($snd_last_name == '') {
		$_SESSION['dev_detail_err']['snd_last_name'] = 'Please enter Last name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if(!$snd_email){
		$_SESSION['dev_detail_err']['snd_email'] = $_ERR['register']['snd_email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}elseif($snd_email!=''){
		if (vpemail($snd_email )){
			$_SESSION['dev_detail_err']['snd_email'] = $_ERR['register']['snd_email'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}else{
			list($username,$domain)=explode('@',$snd_email);
				if(checkdnsrr($domain,'MX')) {
				}else{
					$_SESSION['dev_detail_err']['snd_email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
					header('location:'.ru.'delivery_detail'); exit;
			}
		}
	}
	
	/*if(empty($game_flag)) {
		$_SESSION['dev_detail_err']['game_flag'] = 'Select an option in the section &quot;When Would you like them to complete a game to UNLOCK this iftGift?&quot;';
		header('location:'.ru.'delivery_detail'); exit;
	}*/
	/*if($captions == '') {
		$_SESSION['dev_detail_err']['captions'] = 'Enetr the caption data in the field ';
		header('location:'.ru.'delivery_detail'); exit;
		}
		*/
	if($notify == '') {
		$_SESSION['dev_detail_err']['notify'] = 'Select an option in the section &quot;When should we NOTIFY them about this iftGift?&quot;';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if($notify == '1') {
		if($date_imd == '') {
			$_SESSION['dev_detail_err']['date_imd'] = 'Please set notify Immediately date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($time_imd == '') {
			$_SESSION['dev_detail_err']['time_imd'] = 'Please set notify Immediately time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
	}
	
	if($notify == '2') {
		if($date_future == '') {
			$_SESSION['dev_detail_err']['date_future'] = 'Please set notify Future date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($time_future == '') {
			$_SESSION['dev_detail_err']['time_future'] = 'Please set notify Future time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
	}
	
	if($unlock == '') {
		$_SESSION['dev_detail_err']['unlock'] = 'Select an option in the section &quot;When should we UNLOCK them about this iftGift?&quot;';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if($unlock == '1') {
		if($uidate_imd == '') {
			$_SESSION['dev_detail_err']['uidate_imd'] = 'Please set unlock Immediately date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($uftime_imd == '') {
			$_SESSION['dev_detail_err']['uftime_imd'] = 'Please set unlock Immediately time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
	}
	
	if($unlock == '2') {
		if($udate_future == '') {
			$_SESSION['dev_detail_err']['udate_future'] = 'Please set unlock Future date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($utime_future == '') {
			$_SESSION['dev_detail_err']['utime_future'] = 'Please set unlock Future time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		
	}
	
	
	$userId = $_SESSION['LOGINDATA']['USERID'];
	$cash_amount = str_replace("$","",$amount);
	$notification = '1';
  	if($notification = '1' && $notify == '1') {
		$immed = $notify;
		$date = $date_imd;
		$time = $time_imd;
		$idate_time =  convert_unixdatetime($date,$time);
	} else if($notification = '1' && $notify == '2') {
		$future = $notify;
		$date = $date_future;
		$time = $time_future;
		$idate_time =  convert_unixdatetime($date,$time);
	}
  	$unlocks = '2';
	if($unlocks = '2' && $unlock == '1') {
		$uimmed = $unlock;
		$unlock_date = $uidate_imd;
		$unlock_time = $uftime_imd;
		$fdate_time =  convert_unixdatetime($unlock_date,$unlock_time);
	} else if($unlocks = '2' && $unlock == '2') {
		$ufuture = $unlock;
		$unlock_date = $udate_future;
		$unlock_time = $utime_future;
		$fdate_time =  convert_unixdatetime($unlock_date,$unlock_time);
	}
	foreach ($proid as $index => $value1) {
     	$value2 = $captions[$index];
	 	$value1 = $proid[$index];
    	$pro[] = array('proid' => "$value1" ,'caption' => "$value2");
	}
	$json = mysql_real_escape_string(json_encode($pro));
if($draft_id > 0) {
		
		 $chkdraft = mysql_query("select delivery_id from ".tbl_delivery." where delivery_id = '".$draft_id."'"); 
		if(mysql_num_rows($chkdraft) > 0){
			$insQry = "update ".tbl_delivery." set cash_amount = '$cash_amount',
			  									occassionid			= '".mysql_real_escape_string($occassionid)."',
			  									giv_first_name		= '$snd_first_name',
			 									giv_last_name 		= '$snd_last_name',
												giv_email 			= '$snd_email',
												recp_first_name		= '$recp_first_name',
			 									recp_last_name 		= '$recp_last_name',
												recp_email 			= '$recp_email',
												notification		= '$notification',
			 									immediately 		= '$immed',
												game_flag	= '$game_flag',
												future		=	'$future',
												date 		= '$date',
												time		= '$time',
												idate_time  = '$idate_time',
												unlocks		= '$unlocks',
			 									unlock_immediately 	= '$uimmed',
												unlock_future 		= '$ufuture',
												unlock_date 		= '$unlock_date',
												unlock_time		= '$unlock_time',
												fdate_time		= '$fdate_time',
												proid			= '$json',
												email_subject	= '".mysql_real_escape_string($email_sub)."',
												notes			= '".mysql_real_escape_string($notes)."',
												deliverd_status = 'pending',
												unlock_status	= '1',
												draft 			= '0',
												dated			= '$dated'
											    where delivery_id = '".$draft_id."'";
											// $insQry; exit; 
			$ExQry = mysql_query($insQry)or die (mysql_error());
		$_SESSION['delivery_id']['New'] = $draft_id;		
		}else{
     $insQry ="insert into ".tbl_delivery." set cash_amount = '$cash_amount',
			  									occassionid			= '".mysql_real_escape_string($occassionid)."',
			  									giv_first_name		= '$snd_first_name',
			 									giv_last_name 		= '$snd_last_name',
												giv_email 			= '$snd_email',
												recp_first_name		= '$recp_first_name',
			 									recp_last_name 		= '$recp_last_name',
												recp_email 			= '$recp_email',
												notification		= '$notification',
			 									immediately 		= '$immed',
												game_flag	= '$game_flag',
												future		=	'$future',
												date 		= '$date',
												time		= '$time',
												idate_time  = '$idate_time',
												unlocks		= '$unlocks',
			 									unlock_immediately 	= '$uimmed',
												unlock_future 		= '$ufuture',
												unlock_date 		= '$unlock_date',
												unlock_time		= '$unlock_time',
												fdate_time		= '$fdate_time',
												proid			= '$json',
												email_subject	= '".mysql_real_escape_string($email_sub)."',
												notes			= '".mysql_real_escape_string($notes)."',
												deliverd_status = 'pending',
												unlock_status	= '1',
												userId			= '$userId',
												dated			= '$dated' ";
												//echo $insQry;  exit;
				$ExQry = mysql_query($insQry)or die (mysql_error());
				$_SESSION['delivery_id']['New'] = mysql_insert_id();								
			}									
		}
		
		if($ExQry) {
			unset($_SESSION['dev_detail_err']);
			unset($_SESSION['dev_detail']);
			header("location:".ru."checkout");exit;
		}										
}

//////////////////////////////////////SAVE & RESUME GIFT/////////////////////////////////////////////////

if(isset($_POST['SaveDraft'])) {
	foreach ($_POST as $k => $v ){
		$$k =  $v;
		$_SESSION['dev_detail'][$k]=$v;
	}
	if($recp_first_name == '') {
		$_SESSION['dev_detail_err']['recp_first_name'] = 'Please enter First Name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}
	if(!$recp_email){
		$_SESSION['dev_detail_err']['recp_email'] = $_ERR['register']['recp_email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}elseif($recp_email!=''){
		if (vpemail($recp_email )){
			$_SESSION['dev_detail_err']['recp_email'] = $_ERR['register']['recp_email'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}else{
			list($username,$domain)=explode('@',$recp_email);
				if(checkdnsrr($domain,'MX')) {
				}else{
				  $_SESSION['dev_detail_err']['recp_email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
					header('location:'.ru.'delivery_detail'); exit;
			}
		}
	}
	
	if($snd_first_name == '') {
		$_SESSION['dev_detail_err']['snd_first_name'] = 'Please enter First Name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if($snd_last_name == '') {
		$_SESSION['dev_detail_err']['snd_last_name'] = 'Please enter Last name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if(!$snd_email){
		$_SESSION['dev_detail_err']['snd_email'] = $_ERR['register']['snd_email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'delivery_detail'); exit;
	}elseif($snd_email!=''){
		if (vpemail($snd_email )){
			$_SESSION['dev_detail_err']['snd_email'] = $_ERR['register']['snd_email'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}else{
			list($username,$domain)=explode('@',$snd_email);
				if(checkdnsrr($domain,'MX')) {
				}else{
					$_SESSION['dev_detail_err']['snd_email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
					header('location:'.ru.'delivery_detail'); exit;
			}
		}
	}
	
	/*if(empty($game_flag)) {
		$_SESSION['dev_detail_err']['game_flag'] = 'Select an option in the section &quot;When Would you like them to complete a game to UNLOCK this iftGift?&quot;';
		header('location:'.ru.'delivery_detail'); exit;
	}*/
	/*if($captions == '') {
		$_SESSION['dev_detail_err']['captions'] = 'Enetr the caption data in the field ';
		header('location:'.ru.'delivery_detail'); exit;
		}
		*/
	/*if($notify == '') {
		$_SESSION['dev_detail_err']['notify'] = 'Select an option in the section &quot;When should we NOTIFY them about this iftGift?&quot;';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if($notify == '1') {
		if($date_imd == '') {
			$_SESSION['dev_detail_err']['date_imd'] = 'Please set notify Immediately date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($time_imd == '') {
			$_SESSION['dev_detail_err']['time_imd'] = 'Please set notify Immediately time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
	}
	
	if($notify == '2') {
		if($date_future == '') {
			$_SESSION['dev_detail_err']['date_future'] = 'Please set notify Future date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($time_future == '') {
			$_SESSION['dev_detail_err']['time_future'] = 'Please set notify Future time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
	}
	
	if($unlock == '') {
		$_SESSION['dev_detail_err']['unlock'] = 'Select an option in the section &quot;When should we UNLOCK them about this iftGift?&quot;';
		header('location:'.ru.'delivery_detail'); exit;
	}
	
	if($unlock == '1') {
		if($uidate_imd == '') {
			$_SESSION['dev_detail_err']['uidate_imd'] = 'Please set unlock Immediately date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($uftime_imd == '') {
			$_SESSION['dev_detail_err']['uftime_imd'] = 'Please set unlock Immediately time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
	}
	if($unlock == '2') {
		if($udate_future == '') {
			$_SESSION['dev_detail_err']['udate_future'] = 'Please set unlock Future date field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		if($utime_future == '') {
			$_SESSION['dev_detail_err']['utime_future'] = 'Please set unlock Future time field.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'delivery_detail'); exit;
		}
		
	}*/
	
	
	$userId = $_SESSION['LOGINDATA']['USERID'];
	$cash_amount = str_replace("$","",$amount);
	$notification = '1';
  	if($notification = '1' && $notify == '1') {
		$immed = $notify;
		$date = $date_imd;
		$time = $time_imd;
		$idate_time =  convert_unixdatetime($date,$time);
	} else if($notification = '1' && $notify == '2') {
		$future = $notify;
		$date = $date_future;
		$time = $time_future;
		$idate_time =  convert_unixdatetime($date,$time);
	}
  	$unlocks = '2';
	if($unlocks = '2' && $unlock == '1') {
		$uimmed = $unlock;
		$unlock_date = $uidate_imd;
		$unlock_time = $uftime_imd;
		$fdate_time =  convert_unixdatetime($unlock_date,$unlock_time);
	} else if($unlocks = '2' && $unlock == '2') {
		$ufuture = $unlock;
		$unlock_date = $udate_future;
		$unlock_time = $utime_future;
		$fdate_time =  convert_unixdatetime($unlock_date,$unlock_time);
	}
	foreach ($proid as $index => $value1) {
     	$value2 = $captions[$index];
	 	$value1 = $proid[$index];
    	$pro[] = array('proid' => "$value1" ,'caption' => "$value2");
	}
	$json = mysql_real_escape_string(json_encode($pro));
	
	if($draft_id > 0) {
		
		$chkdraft = mysql_query("select delivery_id from ".tbl_delivery." where delivery_id = '".$draft_id."'");
		if(mysql_num_rows($chkdraft) > 0){
			$insQry = "update ".tbl_delivery." set step = 'delivery_detail',
														notification		= '$notification',
														game_flag			= '$game_flag',
														future				= '$future',
														date 				= '$date',
														time				= '$time',
														unlocks				= '$unlocks',
														unlock_future 		= '$ufuture',
														unlock_date 		= '$unlock_date',
														unlock_time		= '$unlock_time',
														proid			='$json',
														email_subject	= '".mysql_real_escape_string($email_sub)."',
														notes			= '".mysql_real_escape_string($notes)."',
														game_flag		= '$game_flag',
														draft = '1'
														 where delivery_id = '".$draft_id."'";
														//echo $insQry; exit; 
					
		}else{
			$insQry ="insert into ".tbl_delivery." set	cash_amount			= '$cash_amount',
			  											occassionid			= '$occassionid',
			  											recp_first_name		= '$recp_first_name',
			 											recp_last_name 		= '$recp_last_name',
														recp_email 			= '$recp_email',
														giv_first_name		= '$snd_first_name',
														giv_last_name 		= '$snd_last_name',
														giv_email 			= '$snd_email',
														notification		= '$notification',
														game_flag			= '$game_flag',
														future				= '$future',
														date 				= '$date',
														time				= '$time',
														unlocks				= '$unlocks',
														unlock_future 		= '$ufuture',
														unlock_date 		= '$unlock_date',
														unlock_time		= '$unlock_time',
														proid			= '$json',
														email_subject	= '".mysql_real_escape_string($email_sub)."',
														notes			= '".mysql_real_escape_string($notes)."',
														deliverd_status = 'pending',
														unlock_status	= '1',
														userId			= '$userId',
														draft			= '1',
														step 			= 'delivery_detail',
														dated			= '$dated'";
														
													// echo $insQry; exit;
		}
		
		//$delrecp = mysql_query("delete from ".tbl_recipient." where recipit_id = '".$draft_id."'");	
	} 									
		$ExQry = mysql_query($insQry)or die (mysql_error());
		if($ExQry) {
			unset($_SESSION['dev_detail_err']);
			unset($_SESSION['dev_detail']);
			unset($_SESSION['cart']);
			//unset($_SESSION['DRAFT']);
			unset($_SESSION['recipit_id']['New']);
			header("location:".ru."dashboard");exit;
		}										
}
//////////////////////////////////////SAVE & RESUME GIFT/////////////////////////////////////////////////

//////////////////////////////////////UPDATE CASH GIFT/////////////////////////////////////////////////
if(isset($_POST['edit_cash'])) {

	$dId = $_POST['did'];
	$cash = str_replace("$","",$_POST['cash']);
	$UpQry = mysql_query("update ".tbl_delivery." set cash_amount = '".$cash."' where delivery_id = '".$dId."'");
	
	$get_devdetails = "select cash_amount from ".tbl_delivery." where delivery_id = '".$dId."'";
	$dev_details = $db->get_row($get_devdetails,ARRAY_A);
	$cash_amount = $dev_details['cash_amount'];
	$calculate_tax = number_format($dev_details['cash_amount'] /100 * 4.00,'2');
	$add_fee = number_format($dev_details['cash_amount'] + $calculate_tax,'2');
	echo '$'.$cash_amount.'=$'.$calculate_tax.'=$'.$add_fee;
}
//////////////////////////////////////SEND GIFT/////////////////////////////////////////////////
if (isset($_POST['SendGift'])){ 
//print_r($_POST);exit;
     	unset($_SESSION['biz_gift_err']);
	    unset($_SESSION['biz_gift']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_gift'][$k]=$v;
	}
  	$flgs = false;
	
	if(isset($_SESSION['DRAFT'])) {
		if($checkout_method == '') {
			$_SESSION['biz_gift_err']['checkout_method'] = 'Please Select Transfer Fund Method!';
			header('location:'.ru.'checkout'); exit;
		}
	}	

  if($flgs)
  {
	
		header('location:'.ru.'checkout'); exit;
		
  }else{
  		
  
		if($checkout_method == 'credit_card') {
			include('../stripe/lib/Stripe.php');
			Stripe::setApiKey(STRIPE_SECRET);	
			$amount = str_replace(",","",str_replace("$","",$total_cash));
			try{
				$token = Stripe_Token::create(array(
							"card" => array(
								"number" => $cardnumber,
								"exp_month" => $month,
								"exp_year" => $year,
								"cvc" => $cvc
								)
							));
				//echo '<pre>';print_r($token);exit;
			}
			catch(Exception $e){
				$_SESSION['stripe_error'] = $e->getMessage();
				header('Location:'.ru.'checkout');exit;
			}
			
			try{
				$charge = Stripe_Charge::create(array(
						"amount" => $amount*100,
						"currency" => 'usd',
						"source" => $token->id,
						"description" => $email
					));
				//echo '<pre>';print_r($charge);exit;	
			}catch(Exception $e){
				$_SESSION['stripe_error'] = $e->getMessage();
				header('Location:'.ru.'checkout');exit;
			}				
			
			
			if($charge->paid == '1') {	
			 $member_card_info = mysql_query("insert into ".tbl_member_card." set fname = '".$_POST['fname']."', lname = '".$_POST['lname']."', address1 = '".$address1."', address2 = '".$address2."', state = '".$state."', city = '".$city."', zip = '".$zip."', userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."', payment_method = '".$checkout_method."', dated ='".$server."' ");
				$query = mysql_query("insert into ".tbl_payment." set fname = '".$_POST['fname']."', lname = '".$_POST['lname']."', address1 = '".$address1."', address2 = '".$address2."', state = '".$state."', city = '".$city."', zip = '".$zip."', userId = '".$userId."', transactionID = '".$charge->id."', ip = '".$_SERVER['REMOTE_ADDR']."', total_price = '$".$amount."', commission = '".$calculate_tax."', netamount = '".$cash_gift."', payment_method = '".$checkout_method."', dated = '".$server."'");
				$insQry =mysql_query("insert into ".tbl_checkout." set delivery_id	= '".$delivery_id."',
			  									cash_gift 		= '".str_replace("$","",$cash_gift)."',
			  									total_cash		= '".str_replace("$","",$total_cash)."',
												userId			= '".$userId."',
												payment_method  = '".$checkout_method."',
												commission		= '".str_replace("$","",$calculate_tax)."',
												ip				= '".$_SERVER['REMOTE_ADDR']."',
												dated			= '".$server."'");
				
			
				
					$check_points = "select * from ".tbl_userpoints." where userId = '".$userId."'";
					$view_points = $db->get_row($check_points,ARRAY_A);
					$points = $view_points['points'];
					$new_points = $points + 75;
					if($view_points) {
						$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
					} else {
						$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '75',userId = '".$userId."'");
					}	
				
			}
			
			$updateDelivery = mysql_query("update ".tbl_delivery." set draft = '0', step = '' where delivery_id = '".$delivery_id."'");
			
		} else if($checkout_method == 'cash_stash') { 
		
			$get_userstach = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
			$view_userstach = $db->get_row($get_userstach,ARRAY_A);
			$useravailable_cash = $view_userstach['available_cash'];
			$total_cash		= str_replace(",","",str_replace("$","",$total_cash));	
			
			if($total_cash > $useravailable_cash) {
				$insQry =mysql_query("insert into ".tbl_checkout." set delivery_id	= '".$delivery_id."',
			  									cash_gift 		= '".str_replace("$","",$cash_gift)."',
			  									total_cash		= '".str_replace("$","",$total_cash)."',
												userId			= '".$userId."',
												payment_method  = '".$checkout_method."',
												commission		= '".str_replace("$","",$calculate_tax)."',
												ip				= '".$_SERVER['REMOTE_ADDR']."',
												dated			= '".$server."'");
		 		if($insQry) {
					$get_user = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
					$view_user = $db->get_row($get_user,ARRAY_A);
					$sfirst_name = $view_user['first_name'];
					$available_cash = $view_user['available_cash'] - str_replace("$","",$total_cash);
					if($available_cash < 0 )
					{
						$available_cashs = '0.00';
					}
					$update = mysql_query("update ".tbl_user." set available_cash = '".$available_cashs."' where userId = '".$userId."'");
					
					$check_points = "select * from ".tbl_userpoints." where userId = '".$userId."'";
					$view_points = $db->get_row($check_points,ARRAY_A);
					$points = $view_points['points'];
					$new_points = $points + 75;
					if($view_points) {
						$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
					} else {
						$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '75',userId = '".$userId."'");
					}		
		 		}
				//header('location:'.ru.'checkout_step2'); exit;
			} else {
				$insQry =mysql_query("insert into ".tbl_checkout." set delivery_id	= '".$delivery_id."',
			  									cash_gift 		= '".str_replace("$","",$cash_gift)."',
			  									total_cash		= '".str_replace("$","",$total_cash)."',
												userId			= '".$userId."',
												payment_method  = '".$checkout_method."',
												commission		= '".str_replace("$","",$calculate_tax)."',
												ip				= '".$_SERVER['REMOTE_ADDR']."',
												dated			= '".$server."'");
  		 		//$query = mysql_query($insQry)or die (mysql_error());
		 		if($insQry) {
					$get_user = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
					$view_user = $db->get_row($get_user,ARRAY_A);
					$sfirst_name = $view_user['first_name'];
					$available_cash = $view_user['available_cash'] - str_replace("$","",$total_cash);
					$update = mysql_query("update ".tbl_user." set available_cash = '".$available_cash."' where userId = '".$userId."'");
					
					$check_points = "select * from ".tbl_userpoints." where userId = '".$userId."'";
					$view_points = $db->get_row($check_points,ARRAY_A);
					$points = $view_points['points'];
					$new_points = $points + 75;
					if($view_points) {
						$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
					} else {
						$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '75',userId = '".$userId."'");
					}		
		 		}
			}
			
			$updateDelivery = mysql_query("update ".tbl_delivery." set draft = '0', step = '' where delivery_id = '".$delivery_id."'");
			
		} 
		 	
			$getuserdev = @mysql_fetch_array(mysql_query("select * from ".tbl_delivery." where delivery_id = '".$delivery_id."'"));
			$delivery_id = $getuserdev['delivery_id'];
			$userId = $getuserdev['userId'];
			$cash_amount = $getuserdev['cash_amount'];
			$recp_first_name = $getuserdev['recp_first_name'];
			$recp_last_name = $getuserdev['recp_last_name'];
			$recp_email = $getuserdev['recp_email'];
			$giv_first_name = $getuserdev['giv_first_name'];
			$giv_last_name = $getuserdev['giv_last_name'];
			$giv_email = $getuserdev['giv_email'];
			$immediately = $getuserdev['immediately'];
			$unlock_immediately = $getuserdev['unlock_immediately'];
			$future = $getuserdev['future'];
			$timestamps = strtotime($getuserdev['date']);
			$notify_date = date('M d, Y', $timestamps);
			$notify_time = $getuserdev['time'];
			$email_subject = $getuserdev['email_subject'];
			$notes = $getuserdev['notes'];
			$unlock_future = $getuserdev['unlock_future'];
			$delivery_date = $getuserdev['idate_time'];
			$deliverd_status = $getuserdev['deliverd_status'];
			$fdelivery_date = $getuserdev['fdate_time'];
			$unlock_status = $getuserdev['unlock_status'];
			$timestamp = strtotime($getuserdev['unlock_date']);
			$unblock_date = date('M d, Y', $timestamp);
			$proId = $getuserdev['proid'];	
			
			//////////////////////////////////////GIFT GIVER AVATAR/////////////////////////////////////////////////////
	
			$getavatar = @mysql_fetch_array(mysql_query("select userId,user_image from ".tbl_user." where email = '".$giv_email."'"));
			$user_thumbimg = ru.'media/user_image/'.$getavatar['userId'].'/thumb/'.$getavatar['user_image'];
			if(@getimagesize($user_thumbimg)) {
				$user_avatar = $user_thumbimg;
			} else {
				$user_avatar = ru_resource."images/avtar_b.png";
			}
			
			if($immediately == '1' && $unlock_immediately == '1')
			{
			if($recp_email)
			{
					$cdate = date('Y-m-d h:i:s');
					$delivery_datetime = DATE("Y-m-d h:i:s",$delivery_date);
					//$loginlink = ru .'login';
					//$giftaccesslink = ru .'gift_collect';
					$emails = mysql_query("select email,userId from ".tbl_user." where email='$recp_email'");
					if(mysql_num_rows($emails) > 0) {
							$userinfos = mysql_fetch_array($emails);
							$registerlink = ru .'register';
							$giftaccesslink = ru .'gift_collect/'.base64_encode($userinfos['userId'].'_'.$delivery_id);
						 	$loginlink = ru .'login/'.base64_encode($userinfos['userId'].'_'.$delivery_id);
					}else{
						$Qry =mysql_query("insert into ".tbl_user." set first_name='$recp_first_name', last_name='$recp_last_name' , email='$recp_email', user_flag='1' ");
						 $lastId=mysql_insert_id();
						 $registerlink = ru .'register/'.base64_encode($lastId.'_'.$delivery_id);
						 $giftaccesslink = ru .'register/'.base64_encode($lastId.'_'.$delivery_id);
						 $loginlink = ru .'login/'.base64_encode($lastId.'_'.$delivery_id);
							// $_SESSION['REG']=$_SESSION['register']['fname'].','.$_SESSION['register']['email'];
							
						} 
						
					if($deliverd_status == 'pending') {	//echo "here"; exit;
						  
							$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
							$headers .= 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= "Content-type: text/html\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "X-Priority: 1\r\n";
							$headers .= "X-MSMail-Priority: High\r\n";                              
							$subject  = 'It’s Fun Time -'.$giv_first_name.' has sent you an iftGift '.$email_subject;
						
					 		$message  = '<body style="font-family:Arial;">
			<div style="font-family:Arial;width:584px; text-align:center; margin-top:10px; margin:0 auto">
					<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
					</div>
					<div style="font-family:Arial;border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:10px">
					<p style="color:#4d4d4d; font-size:14px;">Hi <span style="font-weight:bold">'.$recp_first_name.',</span></p>
					<p style="color:#4d4d4d; font-size:14px;"><span style="font-weight:bold">'.$giv_first_name.' </span>has sent you an iftGift.</p>
					<p style="color:#4d4d4d; font-size:16px;">iftGifts are suggestions of gifts you’ll love, along with cash you can always use.</p>
					<p style="font-size:14px; font-weight:bold; margin:15px 0 0"><a href="'.$registerlink.'" style="color:#ff33ff; text-decoration:none">First time iftGifters will need to register.</a></p>
					<p style="color:#3399cc; font-size:14px; font-weight:bold; margin:5px 0 0">Returning members, <a href="'.$loginlink.'" style="color:#3399cc; text-decoration:none">just sign-in.</a></p>
					<div style=" width:562px; margin:10px 10px 0; background:#fafafa; border:1px solid #dedede; float:left; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative;">
					<img style=" float:left; margin:10px 0 0 10px; padding-bottom:10px" src="'.$user_avatar.'">
					<p style="float:left; margin-left:15px; font-size:13px; color:#555">'.$notes.'</p>
					</div>
					<p style="margin-bottom:0; display:table; width:100%; float:left;">
					<a href="'.$giftaccesslink.'"><img src="'.ru_resource.'images/btn_c.jpg" alt="Button"></a>
					</p>
					<img src="'.ru_resource.'images/jester_ai.jpg" alt="Jester Image" />
					</div>
					<div style="font-family:Arial;margin:20px 0 0; text-align:center">
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &ndash; All Rights Reserved - iftGiftSM</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
					</div>
</body>';              
						$to=$recp_email;   
						//@mail($to, $subject, $message, $headers);	
						//mail("mamir@zamsol.com","test mail","test mail iftgift rec");		
						sendmail($to, $subject, $message, $headers);			
					}
			}
			
			if($giv_email)
			{ 
				$cdate = date('Y-m-d h:i:s');
				$delivery_datetime = date("Y-m-d h:i:s",$delivery_date);
				
				if($deliverd_status == 'pending') {
					
					$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
					$headers .= 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= "Content-type: text/html\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "X-Priority: 1\r\n";
					$headers .= "X-MSMail-Priority: High\r\n";                                 
					$subject = 'Hooray! Your iftGift to '.$giv_first_name.' is on its way';
			
					$loginlink = ru .'dashboard';
					$pdflink = ru .'process/pdf.php?devId='.$delivery_id;
					$message  = '<body style="font-family:Arial;"><div style="font-family:Arial;width:584px; text-align:center; margin-top:10px; margin:0 auto">
				<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
				</div>
				<div style="font-family:Arial;border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:10px">
				<p style="color:#4d4d4d; font-size:16px;">Hooray! Your iftGift to <span style="font-weight:bold">'.$recp_first_name.'</span>  is on its way.</p>
				<img src="'.ru_resource.'images/icon_r.jpg" alt="Gift Image" />
				<h4 style="font-size:18px; color:#ff9c10; font-weight:bold; margin-bottom:0">Delivery details:</h4>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Total amount sent:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="$'.$cash_amount.'" value="$'.$cash_amount.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px;" />
				</div>
				</div>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Notification email date and time:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="'.$notify_date.' @ '.$notify_time.'" value="'.$notify_date.' @ '.$notify_time.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px; width:220px" />
				</div>
				</div>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Unlock date and time:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="'.$unblock_date.' @ '.$notify_time.'" value="'.$unblock_date.' @ '.$notify_time.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px; width:220px" />
				</div>
				</div>
				<p style="color:#4d4d4d; font-size:14px; margin-top:20px">Would you like to print a Proclamation<br/>to present to the recipient?<br/><strong><a href="'.$pdflink.'" style="color:#4d4d4d;">Click Here</a></strong></p>
				<h4 style="font-size:18px; color:#ff9c10; font-weight:bold; margin-bottom:0">Your suggestions:</h4>
				
				<div style="width:100%; display:table">';
				$proid = json_decode($proId,true);
				if($proid){
					foreach($proid as $pro ){
						$product_id = $pro['proid'];
						$get_pro = "select * from ".tbl_product." where proid = '".$product_id."'";
						$view_pro = $db->get_results($get_pro,ARRAY_A);
						if($view_pro){
							foreach($view_pro as $product){
								if($product['img'] != ''){
									$images=$product['img'];
								}else{
									$images=get_images($product['image_code']);
								}
							$message .='<div style="width:190px; float:left; margin:25px 0 0;height:210px">
								<div style="width:116px; height:116px; border:1px solid #ebecec; margin:0 auto; -moz-box-shadow:0 0 5px 0 #ececec; -webkit-box-shadow:0 0 5px 0 #ececec; box-shadow:0 0 5px 0 #ececec; position:relative;">';
							$message .='<img alt='.$product['pro_name'].' src='.$images.' width="114" height="114" /></div>
								<h4 style="color:#4d4a4a; font-size:15px; font-weight:bold; margin:10px 0 0">'.substr($product['pro_name'],0,50).'</h4>
								<h5 style="color:#4d4a4a; font-size:15px; font-weight:normal; margin:5px 0 0">$'.$product['price'].'</h5>
							</div>';
							}	
						}		
					}
				}
				$message .=	'</div>
				<p style="margin-bottom:0; display:table; width:100%">
				<a href="'.$loginlink.'"><img src="'.ru_resource.'images/btn_f.jpg" /></a>
				</p>
				</div>
				<div style="font-family:Arial;margin:20px 0 0; text-align:center">
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright © 2011, 2012, 2013, 2014, Morris Fritz Friedman – All Rights Reserved - iftGiftSM</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">All ® and TM trademarks/SM service marks are the property of their respective owners</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
				</div></body>';
				//echo $message; exit;
						$to=$giv_email;   
						//@mail($to, $subject, $message, $headers);	
						sendmail($to, $subject, $message, $headers);
				 }
				}
				
				
				//////////////////////////////////////UNLOCK GIFT/////////////////////////////////////////////////////
				  if($delivery_id) {
					
						$update_iftgift = "update ".tbl_delivery." set deliverd_status = 'deliverd', unlock_status = '0', open_status = '2' where delivery_id = '".$delivery_id."'";
						mysql_query($update_iftgift);
						$chk_email = mysql_query("select userId,email,available_cash from ".tbl_user." where email = '".$recp_email."'");
						if(@mysql_num_rows($chk_email) > 0)
						{ 
							$user_info = mysql_fetch_array($chk_email);
							$userId = $user_info['userId'];
							$available_cash = $user_info['available_cash'];
							$new_available_cash = $available_cash + $cash_amount;
							$update_cashstash = mysql_query("update ".tbl_user." set available_cash = '".$new_available_cash."' where userId = '".$userId."'");
						}
				  }
				//////////////////////////////////////UNLOCK GIFT/////////////////////////////////////////////////////	
			} else if($immediately == '1' && $unlock_future == '2')
			{
			if($recp_email)
			{
					$cdate = date('Y-m-d h:i:s');
					$delivery_datetime = DATE("Y-m-d h:i:s",$delivery_date);
					
					//$giftaccesslink = ru .'gift_collect';
					$emails = mysql_query("select email,userId from ".tbl_user." where email='$recp_email'");
					if(mysql_num_rows($emails) > 0) {
							$userinfos = mysql_fetch_array($emails);
							$registerlink = ru .'register';
							$giftaccesslink = ru .'gift_collect/'.base64_encode($userinfos['userId'].'_'.$delivery_id);
							$loginlink = ru .'login/'.base64_encode($userinfos['userId'].'_'.$delivery_id);
					}else{
						 $Qry =mysql_query("insert into ".tbl_user." set first_name='$recp_first_name', last_name='$recp_last_name' , email='$recp_email',user_flag='1' ");
						 $lastId=mysql_insert_id();
						 $registerlink = ru .'register/'.base64_encode($lastId.'_'.$delivery_id);
						 $giftaccesslink = ru .'register/'.base64_encode($lastId.'_'.$delivery_id);
						 $loginlink = ru .'login/'.base64_encode($lastId.'_'.$delivery_id);
						} 
					
					if($deliverd_status == 'pending') {	
						$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
						$headers .= 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= "Content-type: text/html\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "X-Priority: 1\r\n";
						$headers .= "X-MSMail-Priority: High\r\n";   
						
						//$mail->addAddress($recp_email, $recp_first_name);    													
						                            
						$subject = 'It’s Fun Time -'.$giv_first_name.' has sent you an iftGift '.$email_subject;
						
						$message    = '<body style="font-family:Arial;">
			<div style="font-family:Arial;width:584px; text-align:center; margin-top:10px; margin:0 auto">
					<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
					</div>
					<div style="font-family:Arial;border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:10px">
					<p style="color:#4d4d4d; font-size:14px;">Hi <span style="font-weight:bold">'.$recp_first_name.',</span></p>
					<p style="color:#4d4d4d; font-size:14px;"><span style="font-weight:bold">'.$giv_first_name.' </span>has sent you an iftGift.</p>
					<p style="color:#4d4d4d; font-size:16px;">iftGifts are suggestions of gifts you’ll love, along with cash you can always use.</p>
					<p style="font-size:14px; font-weight:bold; margin:15px 0 0"><a href="'.$registerlink.'" style="color:#ff33ff; text-decoration:none">First time iftGifters will need to register.</a></p>
					<p style="color:#3399cc; font-size:14px; font-weight:bold; margin:5px 0 0">Returning members, <a href="'.$loginlink.'" style="color:#3399cc; text-decoration:none">just sign-in.</a></p>
					<div style=" width:562px; margin:10px 10px 0; background:#fafafa; border:1px solid #dedede; float:left; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative;">
					<img style=" float:left; margin:10px 0 0 10px; padding-bottom:10px" src="'.$user_avatar.'">
					<p style="float:left; margin-left:15px; font-size:13px; color:#555">'.$notes.'</p>
					</div>
					<p style="margin-bottom:0; display:table; width:100%; float:left;">
					<a href="'.$giftaccesslink.'"><img src="'.ru_resource.'images/btn_c.jpg" alt="Button"></a>
					</p>
					<img src="'.ru_resource.'images/jester_ai.jpg" alt="Jester Image" />
					</div>
					<div style="font-family:Arial;margin:20px 0 0; text-align:center">
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &ndash; All Rights Reserved - iftGiftSM</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
					</div>
</body>';                   
						$to=$recp_email;   
						//@mail($to, $subject, $message, $headers);	
						sendmail($to, $subject, $message, $headers);						
					}
			}
			
			if($giv_email)
			{ 
				$cdate = date('Y-m-d h:i:s');
				$delivery_datetime = date("Y-m-d h:i:s",$delivery_date);
				
				if($deliverd_status == 'pending') {
					
					$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
					$headers .= 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= "Content-type: text/html\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "X-Priority: 1\r\n";
					$headers .= "X-MSMail-Priority: High\r\n"; 
					
					//$mail->addAddress($giv_email, $giv_first_name);    													
					
					//$mail->isHTML(true);                                
					$subject = 'Hooray! Your iftGift to '.$recp_first_name.' is on its way';
			
					$loginlink = ru .'dashboard';
					$pdflink = ru .'process/pdf.php?devId='.$delivery_id;
					$message  = '<body style="font-family:Arial;"><div style="font-family:Arial;width:584px; text-align:center; margin-top:10px; margin:0 auto">
				<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
				</div>
				<div style="font-family:Arial;border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:10px">
				<p style="color:#4d4d4d; font-size:16px;">Hooray! Your iftGift to <span style="font-weight:bold">'.$recp_first_name.'</span>  is on its way.</p>
				<img src="'.ru_resource.'images/icon_r.jpg" alt="Gift Image" />
				<h4 style="font-size:18px; color:#ff9c10; font-weight:bold; margin-bottom:0">Delivery details:</h4>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Total amount sent:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="$'.$cash_amount.'" value="$'.$cash_amount.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px;" />
				</div>
				</div>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Notification email date and time:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="'.$notify_date.' @ '.$notify_time.'" value="'.$notify_date.' @ '.$notify_time.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px; width:220px" />
				</div>
				</div>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Unlock date and time:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="'.$unblock_date.' @ '.$notify_time.'" value="'.$unblock_date.' @ '.$notify_time.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px; width:220px" />
				</div>
				</div>
				<p style="color:#4d4d4d; font-size:14px; margin-top:20px">Would you like to print a Proclamation<br/>to present to the recipient?<br/><strong><a href="'.$pdflink.'" style="color:#4d4d4d;">Click Here</a></strong></p>
				<h4 style="font-size:18px; color:#ff9c10; font-weight:bold; margin-bottom:0">Your suggestions:</h4>
				
				<div style="width:100%; display:table">';
				$proid = json_decode($proId,true);
				if($proid){
					foreach($proid as $pro ){
						$product_id = $pro['proid'];
						$get_pro = "select * from ".tbl_product." where proid = '".$product_id."'";
						$view_pro = $db->get_results($get_pro,ARRAY_A);
						foreach($view_pro as $product){
							if($product['img'] != ''){
									$images=$product['img'];
								}else{
									$images=get_images($product['image_code']);
							 }
						$message .='<div style="width:190px; float:left; margin:25px 0 0; height:210px">
							<div style="width:116px; height:116px; border:1px solid #ebecec; margin:0 auto; -moz-box-shadow:0 0 5px 0 #ececec; -webkit-box-shadow:0 0 5px 0 #ececec; box-shadow:0 0 5px 0 #ececec; position:relative;">';
						$message .='<img alt='.$product['pro_name'].' src='.$images.' width="114" height="114" /></div>
							<h4 style="color:#4d4a4a; font-size:15px; font-weight:bold; margin:10px 0 0">'.substr($product['pro_name'],0,50).'</h4>
							<h5 style="color:#4d4a4a; font-size:15px; font-weight:normal; margin:5px 0 0">$'.$product['price'].'</h5>
						</div>';
						}			
					}
				}
				$message .=	'</div>
				<p style="margin-bottom:0; display:table; width:100%">
				<a href="'.$loginlink.'"><img src="'.ru_resource.'images/btn_f.jpg" /></a>
				</p>
				</div>
				<div style="font-family:Arial;margin:20px 0 0; text-align:center">
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright © 2011, 2012, 2013, 2014, Morris Fritz Friedman – All Rights Reserved - iftGiftSM</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">All ® and TM trademarks/SM service marks are the property of their respective owners</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
				</div></body>';
				
						$to=$giv_email;   
						//$emails=@mail($to, $subject, $message, $headers);
						$emails = sendmail($to, $subject, $message, $headers);
				if($emails == 'SUCCESS') { 
					$update_devs = mysql_query("update ".tbl_delivery." set deliverd_status = 'deliverd' where delivery_id = '".$delivery_id."'");
				}
				}
				
				}
			} 
		unset($_SESSION['biz_gift_err']);
		unset($_SESSION['biz_gift']);
		unset($_SESSION['cart']);
		//$_SESSION['biz_rec_err']['Recp_edit'] = 'Recipient Info successfully updated!';
		header('location:'.ru.'confirmation'); exit;
  }
}



//////////////////////////////////////SEND GIFT2/////////////////////////////////////////////////
if (isset($_POST['SendGift2'])){ 
//print_r($_POST);exit;
     	unset($_SESSION['biz_gift2_err']);
	    unset($_SESSION['biz_gift2']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_gift2'][$k]=$v;
	}
  	$flgs = false;
	
		if($checkout_method == 'credit_card') {
			include('../stripe/lib/Stripe.php');
			Stripe::setApiKey(STRIPE_SECRET);	
			$amount = str_replace(",","",str_replace("$","",$total_cash));
	 		$charge=Stripe_Charge::create(array("amount" => $amount*100,
                                "currency" => "usd",
								"card" => $_POST['stripeToken'],
								"description" => $email));
			//echo '<pre>';print_r($charge);exit;
			
			if($charge->paid == '1') {
				
				$insQry =mysql_query("insert into ".tbl_checkout." set delivery_id	= '".$delivery_id."',
			  									cash_gift 		= '".str_replace("$","",$cash_gift)."',
			  									total_cash		= '".str_replace("$","",$total_cal_cash)."',
												userId			= '".$userId."',
												payment_method  = 'cash_stash',
												commission		= '".str_replace("$","",$calculate_tax)."',
												ip				= '".$_SERVER['REMOTE_ADDR']."',
												dated			= '".$server."'");
				
			
				
					$get_user = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
					$view_user = $db->get_row($get_user,ARRAY_A);
					$sfirst_name = $view_user['first_name'];
					$available_cash = $view_user['available_cash'] - str_replace(",","",str_replace("$","",$total_cal_cash));
					if($available_cash < 0) {
						$update = mysql_query("update ".tbl_user." set available_cash = '0.00' where userId = '".$userId."'");
					} else {
						$update = mysql_query("update ".tbl_user." set available_cash = '".$available_cash."' where userId = '".$userId."'");
					}
					$check_points = "select * from ".tbl_userpoints." where userId = '".$userId."'";
					$view_points = $db->get_row($check_points,ARRAY_A);
					$points = $view_points['points'];
					$new_points = $points + 75;
					if($view_points) {
						$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
					} else {
						$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '75',userId = '".$userId."'");
					}		
			}
		} else if($checkout_method == 'bank_account') {
				
				$insQry =mysql_query("insert into ".tbl_checkout." set delivery_id	= '".$delivery_id."',
			  									cash_gift 		= '".str_replace("$","",$cash_gift)."',
			  									total_cash		= '".str_replace("$","",$total_cal_cash)."',
												userId			= '".$userId."',
												payment_method  = 'cash_stash',
												commission		= '".str_replace("$","",$calculate_tax)."',
												ip				= '".$_SERVER['REMOTE_ADDR']."',
												dated			= '".$server."'");
				
			
				
					$get_user = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
					$view_user = $db->get_row($get_user,ARRAY_A);
					$sfirst_name = $view_user['first_name'];
					$available_cash = $view_user['available_cash'] - str_replace(",","",str_replace("$","",$total_cal_cash));
					if($available_cash < 0) {
						$update = mysql_query("update ".tbl_user." set available_cash = '0.00' where userId = '".$userId."'");
					} else {
						$update = mysql_query("update ".tbl_user." set available_cash = '".$available_cash."' where userId = '".$userId."'");
					}
					$check_points = "select * from ".tbl_userpoints." where userId = '".$userId."'";
					$view_points = $db->get_row($check_points,ARRAY_A);
					$points = $view_points['points'];
					$new_points = $points + 75;
					if($view_points) {
						$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
					} else {
						$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '75',userId = '".$userId."'");
					}		
		}
		
		$getuserdev = mysql_fetch_array(mysql_query("select * from ".tbl_delivery." where delivery_id = '".$delivery_id."'"));
			$delivery_id = $getuserdev['delivery_id'];
			$userId = $getuserdev['userId'];
			$cash_amount = $getuserdev['cash_amount'];
			$recp_first_name = $getuserdev['recp_first_name'];
			$recp_last_name = $getuserdev['recp_last_name'];
			$recp_email = $getuserdev['recp_email'];
			$giv_first_name = $getuserdev['giv_first_name'];
			$giv_last_name = $getuserdev['giv_last_name'];
			$giv_email = $getuserdev['giv_email'];
			$immediately = $getuserdev['immediately'];
			$unlock_immediately = $getuserdev['unlock_immediately'];
			$future = $getuserdev['future'];
			$timestamps = strtotime($getuserdev['date']);
			$notify_date = date('M d, Y', $timestamps);
			$notify_time = $getuserdev['time'];
			$email_subject = $getuserdev['email_subject'];
			$notes = $getuserdev['notes'];
			$delivery_date = $getuserdev['idate_time'];
			$deliverd_status = $getuserdev['deliverd_status'];
			$fdelivery_date = $getuserdev['fdate_time'];
			$unlock_status = $getuserdev['unlock_status'];
			$timestamp = strtotime($getuserdev['unlock_date']);
			$unblock_date = date('M d, Y', $timestamp);
			$proId = $getuserdev['proid'];	
			
			//////////////////////////////////////GIFT GIVER AVATAR/////////////////////////////////////////////////////
	
			$getavatar = @mysql_fetch_array(mysql_query("select userId,user_image from ".tbl_user." where email = '".$giv_email."'"));
			$user_thumbimg = ru.'media/user_image/'.$getavatar['userId'].'/thumb/'.$getavatar['user_image'];
			if(@getimagesize($user_thumbimg)) {
				$user_avatar = $user_thumbimg;
			} else {
				$user_avatar = ru_resource."images/avtar_b.png";
			}
			
			if($immediately == '1' && $unlock_immediately == '1')
			{
			if($recp_email)
			{
					$cdate = date('Y-m-d h:i:s');
					$delivery_datetime = DATE("Y-m-d h:i:s",$delivery_date);
					
					if($deliverd_status == 'pending') {	
						$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
						$headers .= 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= "Content-type: text/html\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "X-Priority: 1\r\n";
						$headers .= "X-MSMail-Priority: High\r\n"; 
						
						//$mail->addAddress($recp_email, $recp_first_name);    													
						
						                             
						$subject = 'It’s Fun Time -'.$giv_first_name.' has sent you an iftGift '.$email_subject;
																			
						$message    = '<body style="font-family:Arial;">
			<div style="font-family:Arial;width:584px; text-align:center; margin-top:10px; margin:0 auto">
					<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
					</div>
					<div style="font-family:Arial;border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:10px">
					<p style="color:#4d4d4d; font-size:14px;">Hi <span style="font-weight:bold">'.$recp_first_name.',</span></p>
					<p style="color:#4d4d4d; font-size:14px;"><span style="font-weight:bold">'.$giv_first_name.' </span>has sent you an iftGift.</p>
					<p style="color:#4d4d4d; font-size:16px;">iftGifts are suggestions of gifts you’ll love, along with cash you can always use.</p>
					<p style="font-size:14px; font-weight:bold; margin:15px 0 0"><a href="'.$registerlink.'" style="color:#ff33ff; text-decoration:none">First time iftGifters will need to register.</a></p>
					<p style="color:#3399cc; font-size:14px; font-weight:bold; margin:5px 0 0">Returning members, <a href="'.$loginlink.'" style="color:#3399cc; text-decoration:none">just sign-in.</a></p>
					<div style=" width:562px; margin:10px 10px 0; background:#fafafa; border:1px solid #dedede; float:left; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative;">
					<img style=" float:left; margin:10px 0 0 10px; padding-bottom:10px" src="'.$user_avatar.'">
					<p style="float:left; margin-left:15px; font-size:13px; color:#555">'.$notes.'</p>
					</div>
					<p style="margin-bottom:0; display:table; width:100%; float:left;">
					<a href="'.$giftaccesslink.'"><img src="'.ru_resource.'images/btn_c.jpg" alt="Button"></a>
					</p>
					<img src="'.ru_resource.'images/jester_ai.jpg" alt="Jester Image" />
					</div>
					<div style="font-family:Arial;margin:20px 0 0; text-align:center">
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &ndash; All Rights Reserved - iftGiftSM</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
					<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
					</div>
</body>';                   
						$to=$recp_email;   
						//@mail($to, $subject, $message, $headers);	
						 sendmail($to, $subject, $message, $headers);													
					}
			}
			
			if($giv_email)
			{ 
				$cdate = date('Y-m-d h:i:s');
				$delivery_datetime = date("Y-m-d h:i:s",$delivery_date);
				
				if($deliverd_status == 'pending') {
					
						$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
						$headers .= 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= "Content-type: text/html\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "X-Priority: 1\r\n";
						$headers .= "X-MSMail-Priority: High\r\n";
					
					//$mail->addAddress($giv_email, $giv_first_name);    													
					
					                              
					$subject = 'Hooray! Your iftGift to '.$recp_first_name.' is on its way';
					$loginlink = ru .'dashboard';
					$pdflink = ru .'process/pdf.php?devId='.$delivery_id;
						$message  = '<body style="font-family:Arial;"><div style="font-family:Arial;width:584px; text-align:center; margin-top:10px; margin:0 auto">
				<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
				</div>
				<div style="font-family:Arial;border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:10px">
				<p style="color:#4d4d4d; font-size:16px;">Hooray! Your iftGift to <span style="font-weight:bold">'.$recp_first_name.'</span>  is on its way.</p>
				<img src="'.ru_resource.'images/icon_r.jpg" alt="Gift Image" />
				<h4 style="font-size:18px; color:#ff9c10; font-weight:bold; margin-bottom:0">Delivery details:</h4>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Total amount sent:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="$'.$cash_amount.'" value="$'.$cash_amount.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px;" />
				</div>
				</div>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Notification email date and time:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="'.$notify_date.' @ '.$notify_time.'" value="'.$notify_date.' @ '.$notify_time.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px; width:220px" />
				</div>
				</div>
				<div style="width:100%; display:table; margin:10px 0 0">
				<label style="width:46%; margin:0 2% 0 0; float:left; padding:13px 0 0; text-align:right; font-size:14px;">Unlock date and time:</label>
				<div style="width:49%; font-size:14px; color:#8d8d8d; float:left; background:#fafafa; border:1px solid #dedede; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:0 0 1px 0 #dedede; -webkit-box-shadow:0 0 1px 0 #dedede; box-shadow:0 0 1px 0 #dedede">
					<input type="text" placeholder="'.$unblock_date.' @ '.$notify_time.'" value="'.$unblock_date.' @ '.$notify_time.'" disabled="disabled" style="color:#ff9c10; border:0; padding:13px; float:left; background:none; font-size:14px; width:220px" />
				</div>
				</div>
				<p style="color:#4d4d4d; font-size:14px; margin-top:20px">Would you like to print a Proclamation<br/>to present to the recipient?<br/><strong><a href="'.$pdflink.'" style="color:#4d4d4d;">Click Here</a></strong></p>
				<h4 style="font-size:18px; color:#ff9c10; font-weight:bold; margin-bottom:0">Your suggestions:</h4>
				
				<div style="width:100%; display:table">';
				$proid = json_decode($proId,true);
				foreach($proid as $pro )
				{
					$product_id = $pro['proid'];
					$get_pro = "select * from ".tbl_product." where proid = '".$product_id."'";
					$view_pro = $db->get_results($get_pro,ARRAY_A);
					foreach($view_pro as $product)
					{
						if($product['img'] != ''){
								$images=$product['img'];
							}else{
								$images=get_images($product['image_code']);
						}
				$message .='<div style="width:190px; float:left; margin:25px 0 0; height:210px">
					<div style="width:116px; height:116px; border:1px solid #ebecec; margin:0 auto; -moz-box-shadow:0 0 5px 0 #ececec; -webkit-box-shadow:0 0 5px 0 #ececec; box-shadow:0 0 5px 0 #ececec; position:relative;">';
				$message .='<img alt='.$product['pro_name'].' src='.$images.' width="114" height="114" /></div>
					<h4 style="color:#4d4a4a; font-size:15px; font-weight:bold; margin:10px 0 0">'.substr($product['pro_name'],0,50).'</h4>
					<h5 style="color:#4d4a4a; font-size:15px; font-weight:normal; margin:5px 0 0">$'.$product['price'].'</h5>
				</div>';
				}			
				}
				$message .=	'</div>
				<p style="margin-bottom:0; display:table; width:100%">
				<a href="'.$loginlink.'"><img src="'.ru_resource.'images/btn_f.jpg" /></a>
				</p>
				</div>
				<div style="font-family:Arial;margin:20px 0 0; text-align:center">
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright © 2011, 2012, 2013, 2014, Morris Fritz Friedman – All Rights Reserved - iftGiftSM</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">All ® and TM trademarks/SM service marks are the property of their respective owners</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
				<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
				</div></body>';
				
				//$mail->Body = $message;
						$to=$giv_email;   
						//@mail($to, $subject, $message, $headers);
						sendmail($to, $subject, $message, $headers);	
				
					}
				
				}
				//////////////////////////////////////UNLOCK GIFT/////////////////////////////////////////////////////
				  if($delivery_id) {
					
						$update_iftgift = "update ".tbl_delivery." set deliverd_status = 'deliverd', unlock_status = '0', open_status = '2' where delivery_id = '".$delivery_id."'";
						mysql_query($update_iftgift);
						$chk_email = mysql_query("select userId,email,available_cash from ".tbl_user." where email = '".$recp_email."'");
						if(mysql_num_rows($chk_email) > 0)
						{ 
							$user_info = mysql_fetch_array($chk_email);
							$userId = $user_info['userId'];
							$available_cash = $user_info['available_cash'];
							$new_available_cash = $available_cash + $cash_amount;
							$update_cashstash = mysql_query("update ".tbl_user." set available_cash = '".$new_available_cash."' where userId = '".$userId."'");
						}
				  }
				//////////////////////////////////////UNLOCK GIFT/////////////////////////////////////////////////////	
			} 
		 
		unset($_SESSION['biz_gift2_err']);
		unset($_SESSION['biz_gift2']);
		unset($_SESSION['cart']);
		header('location:'.ru.'confirmation'); exit;
}


//////////////////////////////////////Save GIFT/////////////////////////////////////////////////
if (isset($_POST['SaveGift'])){ 
/*print_r($_POST);exit;*/
     	unset($_SESSION['biz_gift_err']);
	    unset($_SESSION['biz_gift']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_gift'][$k]=$v;
	}
  	$flgs = false;
	
	if($delivery_id > 0){
		
		$chkdraft = mysql_query("select delivery_id from ".tbl_delivery." where delivery_id = '".$delivery_id."'");
		
		if(mysql_num_rows($chkdraft) > 0){
		$upDate = mysql_query("update ".tbl_delivery." set step = 'checkout', draft = '1', dated = '".$server."' where delivery_id = '".$delivery_id."'"); 
		}
		
	}
	
	unset($_SESSION['DRAFT']);
	unset($_SESSION['delivery_id']['New']);
	unset($_SESSION['recipit_id']['New']);
	header("location:".ru."dashboard");
}
	
//////////////////////////////////////CANCEL GIFT/////////////////////////////////////////////////

if($_GET['dId'])
{
	
	unset($_SESSION['recipit_id']['New']);
	unset($_SESSION['cart']);
	echo "Success";
	
}

if($_GET['devId'])
{
	
	unset($_SESSION['recipit_id']['New']);
	unset($_SESSION['cart']);
	unset($_SESSION['delivery_id']['New']);
	echo "Success";
	
}

?>