<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once ('../common/function.php');

unset($_SESSION['biz_deposit_err']);
unset($_SESSION['biz_deposit']);
//echo "<pre>";
//print_r($_POST); exit;

$server=date('Y-m-d h:i:s');
//////////////////////////////////////SEND GIFT/////////////////////////////////////////////////
if (isset($_POST['DepositCash'])){ 

     	unset($_SESSION['biz_deposit_err']);
	    unset($_SESSION['biz_deposit']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_deposit'][$k]=$v;
	}
  	$flgs = false;

	
  if($flgs)
  {
	
		header('location:'.ru.'deposit_cash'); exit;
		
  }else{
  		  	
		if($checkout_method == 'credit_card') {
		
			include('../stripe/lib/Stripe.php');
			Stripe::setApiKey(STRIPE_SECRET);
			$amounts = str_replace('$','',$total_amount);
	 		/*$charge=Stripe_Charge::create(array("amount" => $amounts*100,
                                "currency" => "usd",
								"card" => $_POST['stripeToken'],
								"description" => $email));*/		
			//echo '<pre>';print_r($charge);exit;
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
				$_SESSION['stripe_error']  = $e->getMessage();
				header('Location:'.ru.'deposit_cash');exit;
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
				echo $_SESSION['stripe_error'] = $e->getMessage(); 
				header('Location:'.ru.'deposit_cash');exit;
			}
			if($charge->paid == '1') {	
			$member_card_info = mysql_query("insert into ".tbl_member_card." set fname = '".$_POST['fname']."', lname = '".$_POST['lname']."', address1 = '".$address1."', address2 = '".$address2."', state = '".$state."', city = '".$city."', zip = '".$zip."', userId = '".$userId."', ip = '".$_SERVER['REMOTE_ADDR']."', payment_method = '".$checkout_method."', dated = '".$server."' ");	
					 $query = mysql_query("insert into ".tbl_depositcash." set fname = '".$_POST['fname']."', lname = '".$_POST['lname']."', address1 = '".$address1."', address2 = '".$address2."', state = '".$state."', city = '".$city."', zip = '".$zip."', userId = '".$userId."', transactionID = '".$charge->id."', ip = '".$_SERVER['REMOTE_ADDR']."', total_price = '$".$amounts."', commission = '".$calculate_tax."', netamount = '$".$amount."', payment_method = '".$checkout_method."', dated = '".$server."' ");
				
				if($query)
				{
					$get_user = mysql_fetch_array(mysql_query("select userId,available_cash from ".tbl_user." where userId = '".$userId."'"));
					$available_cash = $get_user['available_cash'];
					$netamount = str_replace('$','',$amount);
					$new_cash = $available_cash + $netamount;
					$updt_cash = mysql_query("update ".tbl_user." set available_cash = '".$new_cash."' where userId = '".$userId."'");
				}
			}
		} 
		 
		unset($_SESSION['biz_deposit_err']);
		unset($_SESSION['biz_deposit']);
		$_SESSION['deposit']['amount'] = $amount;
		$_SESSION['biz_deposit_err']['depositcashstash'] = 'Your Transaction Successfully!';
		header('location:'.ru.'deposit_cash'); exit;
  }
  
}
?>