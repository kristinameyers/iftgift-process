<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once("../common/function.php");
 //include('../phpmailer/class.smtp.php');
//include('../phpmailer/class.phpmailer.php');
unset($_SESSION['biz_withdraw_err']);
unset($_SESSION['biz_withdraw']);
//echo "<pre>";
//print_r($_POST); exit;


//////////////////////////////////////SEND GIFT/////////////////////////////////////////////////
if (isset($_POST['WithdrawCash'])){ 

     	unset($_SESSION['biz_withdraw_err']);
	    unset($_SESSION['biz_withdraw']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_withdraw'][$k]=$v;
	}
  	$flgs = false;
	
	if($amount==''){
		$_SESSION['biz_withdraw_err']['amount'] = 'Please enter payment amount. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'withdraw_cash'); exit;
	
	} else if(!is_numeric($amount)) {
		$_SESSION['biz_withdraw_err']['amount'] = 'Please enter Numeric value. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'withdraw_cash'); exit;
	}
	
	/*if($checkout_method==''){
		$_SESSION['biz_withdraw_err']['checkout_method'] = 'Payment method not selected.';
		header('location:'.ru.'withdraw_cash'); exit;
	
	} */
	
	if($checkout_method == 'bank_account') {
		
		if($routing_number==''){
			$_SESSION['biz_withdraw_err']['routing_number'] = 'Please enter routing number. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		} 
		
		if($routing_number != '' && strlen($routing_number) < 9 || strlen($routing_number) > 11) {
			$_SESSION['biz_withdraw_err']['routing_number'] = 'Routing number must 9 digits. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		} 
		$rout_number = explode('-',$routing_number);
		$routing_number = $rout_number[0].''.$rout_number[1].''.$rout_number[2];
		if($account_number==''){
			$_SESSION['biz_withdraw_err']['account_number'] = 'Please enter bank account number. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($account_number != '' && strlen($account_number) < 10 || strlen($account_number) > 12) {
			$_SESSION['biz_withdraw_err']['account_number'] = 'Your account number is incorrect. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		  
		if($account_number != ''){
			if(preg_match('/^\d+$/',$account_number)) {
				$match_card = mysql_query("select acchID,ach_number from ".tbl_achnumber." where ach_number = '".$account_number."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'");
				if(mysql_num_rows($match_card) > 0) {
			 		$get_card = mysql_fetch_array($match_card);
			 		$get_card_num = decrypt($get_card['ach_number']);
			 		if($account_number == $get_card_num) {
			 			$_SESSION['biz_withdraw_err']['account_number'] = 'This account number already Exists.Please check exsiting accounts. <br/> Please correct the field <span>in red.</span>';
						header('location:'.ru.'withdraw_cash'); exit;
			 		}
				}
			} 
		}
		$acct_number = explode('-',$account_number);
		$account_number = $acct_number[0].''.$acct_number[1].''.$acct_number[2];
		if($routing_number != ''){
			if(!preg_match('/^\d+$/',$routing_number)) {
				$_SESSION['biz_withdraw_err']['routing_number'] = 'Routing number contains only digits. <br/> Please correct the field <span>in red.</span>';
				header('location:'.ru.'withdraw_cash'); exit;
			}
		}  
	}
	
	/*if($checkout_method == 'credit_card') {
		$current_month=date("m");
		$current_year=date("Y");
		
		if($cardnumber == '') {
			$_SESSION['biz_withdraw_err']['cardnumber'] = 'Please enter credit card number. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($cardnumber != '' && strlen($cardnumber) < 12 || strlen($cardnumber) > 19) {
			$_SESSION['biz_withdraw_err']['cardnumber'] = 'Your credit card information is incorrect. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($cardnumber != '') {
			if(preg_match('/^\d+$/',$cardnumber)) {
				$match_card = mysql_query("select memberID,card_number from ".tbl_member_card." where card_number = '".encrypt($cardnumber)."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'");
				if(mysql_num_rows($match_card) > 0) {
			 		$get_card = mysql_fetch_array($match_card);
			 		$get_card_num = decrypt($get_card['card_number']);
			 		if($cardnumber == $get_card_num) {
			 			$_SESSION['biz_withdraw_err']['cardnumber'] = 'This card number already Exists.Please check exsiting crads. <br/> Please correct the field <span>in red.</span>';
						header('location:'.ru.'withdraw_cash'); exit;
			 		}
				}
			} 
		}
		$card_number = explode('-',$cardnumber);
		$cardnumber = $card_number[0].''.$card_number[1].''.$card_number[2].''.$card_number[3];
		if($cvv == '') {
			$_SESSION['biz_withdraw_err']['cvv'] = 'Please enter cvv. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($month == '') {
			$_SESSION['biz_withdraw_err']['month'] = 'Please enter expiry Month. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($year == '') {
			$_SESSION['biz_withdraw_err']['year'] = 'Please enter expiry Year. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($month != '' && $year != '') {
			if($month >= $current_month && $year >= $current_year || $month < $current_month && $year > $current_year) {
				
			} else {
				$_SESSION['biz_withdraw_err']['year'] = 'your expiry month or year is invalid. <br/> Please correct the field <span>in red.</span>';
				header('location:'.ru.'withdraw_cash'); exit;
			}
		}
		
		if($fname == '') {
			$_SESSION['biz_withdraw_err']['fname'] = 'Please enter first name. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($lname == '') {
			$_SESSION['biz_withdraw_err']['lname'] = 'Please enter last name. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($address1 == '') {
			$_SESSION['biz_withdraw_err']['address1'] = 'Please enter address. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($city == '') {
			$_SESSION['biz_withdraw_err']['city'] = 'Please enter city. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($state == '' || $state == 'Select State') {
			$_SESSION['biz_withdraw_err']['state'] = 'Please select state. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
		
		if($zip == '') {
			$_SESSION['biz_withdraw_err']['zip'] = 'Please enter zip code. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'withdraw_cash'); exit;
		}
	}*/
	
  if($flgs)
  {
		header('location:'.ru.'withdraw_cash'); exit;
		
  }else{ 
  		$chk_ach = @mysql_fetch_array(mysql_query("select ach_number,acchID from ".tbl_achnumber." where acchID = '".$checkout_method."' and ach_number = '".$account_number."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'"));
		
		$chk_card = @mysql_fetch_array(mysql_query("select payment_method,memberID from ".tbl_member_card." where memberID = '".$checkout_method."' and card_number = '".$cardnumber."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'"));
	
		if($chk_ach != '') { 
			$acchID = $chk_ach['acchID'];
			$collect_card_info = @mysql_fetch_array(mysql_query("select ach_number from ".tbl_achnumber." where acchID = '".$acchID."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'"));	
			$ach_number = $collect_card_info['ach_number'];
			
			if($account_number == $ach_number)
			{
				$member_ach = mysql_query("update ".tbl_achnumber." set routing_number = '".$routing_number."', ach_number = '".$account_number."', userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."',  dated = now() where acchID = '".$acchID."'");
				
			} 
		} else {
		if($checkout_method == 'bank_account') {	
			$member_ach = mysql_query("insert into ".tbl_achnumber." set routing_number = '".$routing_number."', ach_number = '".$account_number."', userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."', dated = now()");
			
		}
	  }	
		if($member_ach) { 
					 $query = mysql_query("insert into ".tbl_withdrawcash." set routing_number = '".encrypt($routing_number)."', ach_number = '".encrypt($account_number)."', userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."', total_price = '".$total_amount."', commission = '".$calculate_tax."', netamount = '$".$amount."',wstatus = 'pending',payment_method = 'bank_account', dated = now()");
				
					$get_user = mysql_fetch_array(mysql_query("select userId,available_cash,email,first_name,last_name from ".tbl_user." where userId = '".$userId."'"));
					$available_cash = $get_user['available_cash'];
					//$netamount = str_replace('$','',$total_amount);
					//$new_cash = $available_cash - $netamount;
					$netamount = str_replace('$','',$amount);
					$new_cash = $available_cash - $netamount;
					$updt_cash = mysql_query("update ".tbl_user." set available_cash = '".$new_cash."' where userId = '".$userId."'");
				}
				
		if($userId) { 
			  $query = mysql_query("insert into gift_cash_withdraw set userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."', total_price = '".$total_amount."', commission = '".$calculate_tax."', netamount = '$".$amount."',wstatus = 'pending', dated = now()");
		
			$get_user = mysql_fetch_array(mysql_query("select userId,available_cash,email,first_name,last_name from ".tbl_user." where userId = '".$userId."'"));
			$available_cash = $get_user['available_cash'];
			//$netamount = str_replace('$','',$total_amount);
			//$new_cash = $available_cash - $netamount; 
			$netamount = str_replace('$','',$amount);
			$new_cash = $available_cash - $netamount;
			$updt_cash = mysql_query("update ".tbl_user." set available_cash = '".$new_cash."' where userId = '".$userId."'");
	}
		
		
		/*******************************************START SEND MAIL OF WITHDRAW CASH [For User]*****************************************************************/
			if($get_user['email'] != '') {
			$to =$get_user['email']; 
			$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n";   
			                             
			$subject = '[iftGift] iftGift Withdraw Cash!';
			$message	= '<body style="font-family:Arial;"><div style="width:630px;border:#c5c4c4 2px solid;  margin:0 auto;background-color:#f6f0fa; font-family:Arial, Helvetica, sans-serif; font-size:12.5px; color:#000;">
  				<div style="border:#ede2f5 thin solid; ">
    				<div style="height:364px;font-size:15px !important; line-height:0.95">
    					<img src="'.ru_resource.'images/logo.png" alt="iftgift" style="margin-left:191px;margin-top:5px;" />
    					<div style="padding-left: 13px; padding-top: 15px;">
      						<div style="-moz-border-radius:6px;-webkit-border-radius:6px;background: #fff;margin-top: 20px;border: 1px solid #cccccc;width: 602px;padding-top: 15px;height: 232px;" >
							        <p style="margin-left:10px"><strong>Dear</strong> '.$get_user['first_name'].' '.$get_user['last_name'].' :</p>
									<p style="margin-left:10px"><strong>Cash Amount</strong> : $'.$amount.'</p>
									<center>
        							<p style="font-weight: bold">Your request has been submitted for processing.</p>
        							<p></p>
      							</center>
      						</div>
      					</div>
    				</div>
  				</div>
  				<center>
      				<p>&nbsp;</p>
      					<table style="color:#726f6f;">
      						<tr>
      							<td><a href="#" style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Home</a></td>
      							<td>|</td>
      							<td><a href="whatisiftgift.php"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">What is <span style="font-weight:bold;color:#ff69ff">i</span><span style="font-weight:bold;color:#3399cc">f</span><span style="font-weight:bold;color:#ff9900">t</span>Gift?</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Schedule of <span style="font-weight:bold;color:#ff69ff">i</span><span style="font-weight:bold;color:#3399cc">f</span><span style="font-weight:bold;color:#ff9900">t</span>Points</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">FAQ</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Contact</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Terms</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Privacy</a></td>
     						</tr>
      					</table>
      					<p style="color:#726f6f;">Protected by one or more the following US Patents and Patents Pending: 8,280,825 and 8,589,314.<br />
        				Copyright � 2011, 2012, 2013 Morris Fritz Friedman � All Rights Reserved � iftGift</p>
      					<p style="color:#726f6f;">This message was sent from a notification-only email address that does not accept incoming email. <br />
        				Please do not reply to this message.</p>
      					<p><a style="color: #726f6f;font-weight: bold; text-decoration: none" href="http://www.iftGift.com" target="_blank">www.iftGift.com</a></p>
  				</center>
  				<div style=" height:250px;"></div>
			</div></body>';
			//echo $to.'<br />'.$subject.'<br />'.$message; exit;
			$emails = sendmail($to, $subject, $message, $headers);
			if($emails == 'SUCCESS') { }
		}	
		/*******************************************END SEND MAIL OF WITHDRAW CASH {For User}*****************************************************************/	
		
		
		/*******************************************START SEND MAIL OF WITHDRAW CASH [For Admin]*****************************************************************/
			$get_admin = mysql_fetch_array(mysql_query("select email from ".tbl_user." where userId = '1' and type = 'a'"));
			if($get_admin != '') { 
			$to = $get_admin['email'];
			$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n";    
			                           
			$subject = '[iftGift] iftGift Withdraw Cash!';
			$message	= '<body style="font-family:Arial;"><div style="width:630px;border:#c5c4c4 2px solid;  margin:0 auto;background-color:#f6f0fa; font-family:Arial, Helvetica, sans-serif; font-size:12.5px; color:#000;">
  				<div style="border:#ede2f5 thin solid; ">
    				<div style="height:364px;font-size:15px !important; line-height:0.95">
    					<img src="'.ru_resource.'images/logo.png" alt="iftgift"  style="margin-left:191px;margin-top:5px;" />
    					<div style="padding-left: 13px; padding-top: 15px;">
      						<div style="-moz-border-radius:6px;-webkit-border-radius:6px;background: #fff;margin-top: 20px;border: 1px solid #cccccc;width: 602px;padding-top: 15px;height: 232px;" >
									<p style="margin-left:10px"><strong>Cash Amount</strong> : $'.$amount.'</p>
									<center>
        							<p style="font-weight: bold">You Received Cash Withdraw Request From '.ucfirst($get_user['first_name']).' '.ucfirst($get_user['last_name']).'.</p>
      							</center>
      						</div>
      					</div>
    				</div>
  				</div>
  				<center>
      				<p>&nbsp;</p>
      					<table style="color:#726f6f;">
      						<tr>
      							<td><a href="#" style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Home</a></td>
      							<td>|</td>
      							<td><a href="whatisiftgift.php"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">What is <span style="font-weight:bold;color:#ff69ff">i</span><span style="font-weight:bold;color:#3399cc">f</span><span style="font-weight:bold;color:#ff9900">t</span>Gift?</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Schedule of <span style="font-weight:bold;color:#ff69ff">i</span><span style="font-weight:bold;color:#3399cc">f</span><span style="font-weight:bold;color:#ff9900">t</span>Points</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">FAQ</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Contact</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Terms</a></td>
      							<td>|</td>
      							<td><a href="#"  style="text-decoration:none; color: #726f6f;padding-left: 5px;padding-right: 5px;">Privacy</a></td>
     						</tr>
      					</table>
      					<p style="color:#726f6f;">Protected by one or more the following US Patents and Patents Pending: 8,280,825 and 8,589,314.<br />
        				Copyright � 2011, 2012, 2013 Morris Fritz Friedman � All Rights Reserved � iftGift</p>
      					<p style="color:#726f6f;">This message was sent from a notification-only email address that does not accept incoming email. <br />
        				Please do not reply to this message.</p>
      					<p><a style="color: #726f6f;font-weight: bold; text-decoration: none" href="http://www.iftGift.com" target="_blank">www.iftGift.com</a></p>
  				</center>
  				<div style=" height:250px;"></div>
			</div></body>';
			//echo '<br />'.$to.'<br />'.$subject.'<br />'.$message; exit;
			//$emails=mail("hp@zamsol.com", $subject, $message, $headers);
			$emails = sendmail("hp@zamsol.com", $subject, $message, $headers);
			if($emails == 'SUCCESS') {}
		}	
		/*******************************************END SEND MAIL OF WITHDRAW CASH {For Admin}*****************************************************************/		
		unset($_SESSION['biz_withdraw_err']);
		unset($_SESSION['biz_withdraw']);
		$_SESSION['withdraw']['amount'] = $amount;
		$_SESSION['biz_withdraw_err']['withdrawcashstash'] = 'Your Cash was Withdrawn Successfully!';
		header('location:'.ru.'withdraw_cash'); exit;
  }
  
}
?>