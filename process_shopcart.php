<?php
include_once('../connect/connect.php');
include_once('../config/config.php');
include_once('../common/function.php');
include_once("cart_functions.php");
//echo '<pre>';
//print_r($_POST);exit; 
$server=date('Y-m-d h:i:s');
if($_REQUEST['type']=='add' && $_REQUEST['proid']>0){
	$pid=$_REQUEST['proid'];
	$userId = $_SESSION['LOGINDATA']['USERID'];
	$pro = explode(',',$pid);
	foreach($pro as $pId)
	{
		addtocart($pId,1);
		$max=count($_SESSION['cart']);
	}	
}

if($_REQUEST['type']=='delete' && $_REQUEST['proid']>0){
	remove_product($_REQUEST['proid']);
}	

if($_GET['pId'])
{
	
	unset($_SESSION['cart']);
	echo "Success";
	
}



if (isset($_POST['Shopcheckout'])){ 
//print_r($_POST);exit;
     	unset($_SESSION['biz_shopcheck_err']);
	    unset($_SESSION['shopcheck_gift']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['shopcheck_gift'][$k]=$v;
	}
  	$flgs = false;


/*	if($checkout_method == 'credit_card') {
		if($cardnumber != ''){
			$match_card = mysql_query("select memberID,card_number from ".tbl_member_card." where card_number = '".encrypt($cardnumber)."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'");
			if(mysql_num_rows($match_card) > 0) {
			 	$get_card = mysql_fetch_array($match_card);
			 	$get_card_num = decrypt($get_card['card_number']);
			 	if($cardnumber == $get_card_num) {
			 		$_SESSION['biz_gift_err']['cardnumber'] = 'This card number already Exists.Please check exsiting crads.';
					$flgs = true;
			 	}
			}
		}
	}*/
	
  if($flgs)
  {
	
		header('location:'.ru.'checkout'); exit;
		
  }else{
  		
		/*$chk_card = @mysql_fetch_array(mysql_query("select payment_method,memberID from ".tbl_member_card." where memberID = '".$checkout_method."' and userId = '".$_SESSION['LOGINDATA']['USERID']."'"));
		$checkout_methods = $chk_card['payment_method'];
		$memberID = $chk_card['memberID'];*/
  	
		if($checkout_method == 'credit_card') { 
			include('../stripe/lib/Stripe.php');
			Stripe::setApiKey(STRIPE_SECRET);
			$current_price		= str_replace(",","",str_replace("$","",$current_price));	
			 $amount = str_replace(",","",str_replace("$","",$total_price)); 
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
				header('Location:'.ru.'buildcheckoutshop');exit;
			}
			
			try{
				$charge = Stripe_Charge::create(array(
						"amount" => round($amount*100),
						"currency" => 'usd',
						"source" => $token->id,
						"description" => $email
					));
				//echo '<pre>';print_r($charge);exit;	
			}catch(Exception $e){
				$_SESSION['stripe_error'] = $e->getMessage(); 
				header('Location:'.ru.'buildcheckoutshop');exit;
			}		
			
			if($charge->paid == '1') {
				$member_card_info = mysql_query("insert into ".tbl_member_card." set fname = '".$_POST['fname']."', lname = '".$_POST['lname']."', address1 = '".$address1."', address2 = '".address2."', state = '".$state."', city = '".$city."', zip = '".$zip."', userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."', payment_method = '".$checkout_method."', dated = '".$server."'  ");
				
				$max=count($_SESSION['cart']);
				$insOrdr = "insert into ".tbl_order." set customerID	= '".$userId."',
			  									num_of_item 		= '".$max."',
			  									net_amount		= '".$current_price."',
												tax			= '".$sale_tax."',
												shipping	= '".$shipping."',
												total_cost  = '".$amount."',
												ostatus		= 'pending',
												transactionID = '".$charge->id."',
												payment_method = 'credit_card',
												ip = '".$_SERVER['REMOTE_ADDR']."',
												dated		  = '".$server."' ";
				$query = mysql_query($insOrdr)or die (mysql_error());									
				$orderid=mysql_insert_id();		
				
				$insQry ="insert into ".tbl_shipping_address." set customerID	= '".$userId."',
												orderID			= '".$orderid."',
			  									cus_fname 		= '".$shipfname."',
			  									cus_lname		= '".$shiplname."',
												cus_address  = '".$shipaddress."',
												ship_city = '".$shipcity."',
												ship_state = '".$shipstate."',
												ship_zip	= '".$shipzip."'";
  		 		$query1 = mysql_query($insQry)or die (mysql_error());
				
				for($i=0;$i<$max;$i++){
				$pid=$_SESSION['cart'][$i]['proid'];
				$q=$_SESSION['cart'][$i]['qty'];
				$price=get_prices($pid);
				mysql_query("insert into ".tbl_order_detail." set orderID = '".$orderid."',
											   product_id = '".$pid."',
											   pro_qty    = '".$q."',
											   price	  = '".$price."',
											   dated	  = '".$server."' ");								   
				}								
			
				if($charge->paid == '1') {
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
		} else if($checkout_method == 'cash_stash') {
		
			$get_userstach = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
			$view_userstach = $db->get_row($get_userstach,ARRAY_A);
			$useravailable_cash = $view_userstach['available_cash'];
		
			$current_price		= str_replace(",","",str_replace("$","",$current_price));
			$total_cash		= number_format(str_replace(",","",str_replace("$","",$total_price)));	
		
			if($total_cash > $useravailable_cash) {
				$_SESSION['total']['price'] = $total_price;
				$_SESSION['shipping']['fname'] = $shipfname;
			  	$_SESSION['shipping']['lname'] = $shiplname;
				$_SESSION['shipping']['address'] = $shipaddress;
				$_SESSION['shipping']['city'] = $shipcity;
				$_SESSION['shipping']['state'] = $shipstate;
				$_SESSION['shipping']['zip'] = $shipzip;
				header('location:'.ru.'buildshopcheckout2'); exit;
			} else {
			$max=count($_SESSION['cart']);
			$insOrdr = "insert into ".tbl_order." set customerID	= '".$userId."',
			  									num_of_item 		= '".$max."',
			  									net_amount		= '".$current_price."',
												tax			= '".$sale_tax."',
												shipping			= '".$shipping."',
												total_cost  = '".$total_price."',
												ostatus		= 'pending',
												payment_method = '".$checkout_method."',
												ip = '".$_SERVER['REMOTE_ADDR']."',
												dated		  = '".$server."' ";
					$query = mysql_query($insOrdr)or die (mysql_error());									
					$orderid=mysql_insert_id();	
				$insQry ="insert into ".tbl_shipping_address." set customerID	= '".$userId."',
												orderID			= '".$orderid."',
			  									cus_fname 		= '".$shipfname."',
			  									cus_lname		= '".$shiplname."',
												cus_address  = '".$shipaddress."',
												ship_city = '".$shipcity."',
												ship_state = '".$shipstate."',
												ship_zip	= '".$shipzip."'";	
				$query1 = mysql_query($insQry)or die (mysql_error());
				
				for($i=0;$i<$max;$i++){
				$pid=$_SESSION['cart'][$i]['proid'];
				$q=$_SESSION['cart'][$i]['qty'];
				$price=get_prices($pid);
				mysql_query("insert into ".tbl_order_detail." set orderID = '".$orderid."',
											   product_id = '".$pid."',
											   pro_qty    = '".$q."',
											   price	  = '".$price."',
											   dated	  = '".$server."' ");								   
				}	
				
				if($query1)
		 		{
					$get_user = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
					$view_user = $db->get_row($get_user,ARRAY_A);
					$sfirst_name = $view_user['first_name'];
					$available_cash = $view_user['available_cash'] - $total_price;
					$update = mysql_query("update ".tbl_user." set available_cash = '".$available_cash."' where userId = '".$userId."'");	
		 		}										
			}
		} 
		 
			
			
		unset($_SESSION['biz_shopcheck_err']);
		unset($_SESSION['shopcheck_gift']);
		unset($_SESSION['cart']);
		$_SESSION['total']['price'] = $total_price;
		//$_SESSION['biz_rec_err']['Recp_edit'] = 'Recipient Info successfully updated!';
		header('location:'.ru.'confirmation'); exit;
  }
  
}


if (isset($_POST['Shopcheckout2'])){ 
//print_r($_POST);exit;
     	unset($_SESSION['biz_shopcheck2_err']);
		unset($_SESSION['shopcheck2_gift']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['shopcheck2_gift'][$k]=$v;
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
				
				$max=count($_SESSION['cart']);
				$current_price = $total_cal_cash - 10;
				$insOrdr = "insert into ".tbl_order." set customerID	= '".$userId."',
			  									num_of_item 		= '".$max."',
			  									net_amount		= '".$current_price."',
												tax			= '5.00',
												shipping	= '5.00',
												total_cost  = '".$total_cal_cash."',
												ostatus		= 'pending',
												transactionID = '".$charge->id."',
												payment_method = 'credit_card',
												ip = '".$_SERVER['REMOTE_ADDR']."',
												dated		  = '".$server."' ";
				$query = mysql_query($insOrdr)or die (mysql_error());									
				$orderid=mysql_insert_id();		
				
				$insQry ="insert into ".tbl_shipping_address." set customerID	= '".$userId."',
												orderID			= '".$orderid."',
			  									cus_fname 		= '".$shipfname."',
			  									cus_lname		= '".$shiplname."',
												cus_address  = '".$shipaddress."',
												ship_city = '".$shipcity."',
												ship_state = '".$shipstate."',
												ship_zip	= '".$shipzip."'";
  		 		$query1 = mysql_query($insQry)or die (mysql_error());
				
				for($i=0;$i<$max;$i++){
				$pid=$_SESSION['cart'][$i]['proid'];
				$q=$_SESSION['cart'][$i]['qty'];
				$price=get_prices($pid);
				mysql_query("insert into ".tbl_order_detail." set orderID = '".$orderid."',
											   product_id = '".$pid."',
											   pro_qty    = '".$q."',
											   price	  = '".$price."',
											   dated	  = '".$server."'  ");								   
				}								
				
			
				
					$get_user = "select available_cash,first_name,email from ".tbl_user." where userId = '".$userId."'";
					$view_user = $db->get_row($get_user,ARRAY_A);
					$sfirst_name = $view_user['first_name'];
					$available_cash = $view_user['available_cash'] - str_replace(",","",str_replace("$","",$total_cal_cash));
					if($available_cash < 0) {
						$update = mysql_query("update ".tbl_user." set available_cash = '0.00' where userId = '".$userId."'");
					} else {
						$update = mysql_query("update ".tbl_user." set available_cash = '".$available_cash."' where userId = '".$userId."'");
					}
			}
		} 
		
		
		unset($_SESSION['biz_shopcheck2_err']);
		unset($_SESSION['shopcheck2_gift']);
		unset($_SESSION['cart']);
		$_SESSION['total']['price'] = $total_cal_cash;
		unset($_SESSION['shipping']);
		header('location:'.ru.'confirmation'); exit;
}
?>