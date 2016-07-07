<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once ('../common/function.php');

unset($_SESSION['biz_rep_err']);
unset($_SESSION['biz_rep']);
/*echo "<pre>";
print_r($_POST); exit;*/
if (isset($_POST['SaveRecipit'])){ 
     	unset($_SESSION['biz_rep_err']);
	    unset($_SESSION['biz_rep']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_rep'][$k]=$v;
	}
  	$flgs = false;
	///////////////////////name validation////////	
	
	if($cash_amount=='' && $first_name=='' && $email=='' && $gender=='Select' && $age=='' && $ocassion=='Event') {
		
		$_SESSION['biz_rep_err']['errors'] = 'There are too many empty fields. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
		
	}
	
	$cash_amounts = str_replace('$','',$cash_amount);
	if($cash_amounts==''){
		$_SESSION['biz_rep_err']['cash_amount'] = 'You did not enter a cash gift amount. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	} else if(!is_numeric($cash_amounts)) {
		$_SESSION['biz_rep_err']['cash_amount'] = 'Please enter Numeric value. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	}
	
	if($first_name==''){
		$_SESSION['biz_rep_err']['first_name'] = 'Please enter First name. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}
	
	if($email==''){
		$_SESSION['biz_rep_err']['email'] = $_ERR['register']['email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}elseif($email!=''){
			
			if (vpemail($email )){
			
				$_SESSION['biz_rep_err']['email'] = $_ERR['register']['emailg'].'<br/> Please correct the field <span>in red.</span>';
				header('location:'.ru.'step_1'); exit;
			
			}else {
				list($username,$domain)=explode('@',$email);
				if(checkdnsrr($domain,'MX')) {
					
				}else{
					$_SESSION['biz_rep_err']['email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
					header('location:'.ru.'step_1'); exit;
				}
			}	
	}	
	
	if($gender=='Select'){
		$_SESSION['biz_rep_err']['gender'] = 'You did not select gender. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}	
	
	if($age==''){
		$_SESSION['biz_rep_err']['age'] = 'Please enter age. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}	
	
	if($ocassion=='Event' && $ocassion1 == ''){
			$_SESSION['biz_rep_err']['ocassion'] = 'You did not select/enter ocassion. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'step_1'); exit;	
	} 
	if($ocassion == 'other' && $ocassion1 == ''){
			$_SESSION['biz_rep_err']['ocassion1'] = 'You did not select/enter ocassion. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'step_1'); exit;	
	}
	
	if($ocassion == 'other'){
		$ocassions =  'other_'.$ocassion1;
	} else if($ocassion != 'Event'){
		$ocassions = $ocassion;
	}
	  
  if($flgs)
  {
	
		header('location:'.ru.'step_1'); exit;
		
  }else{
 if(isset($recipt_id)){
	 		 $update ="update  ".tbl_delivery." set cash_amount   = '$cash_amounts',
													recp_first_name		= '$first_name',
													recp_last_name 		= '$last_name',
													recp_email 			= '$email',
													gender   		= '$gender',
													age     	    = '$age',
													location 		= '$location',
													occassionid 		= '$ocassions',
													userId			= '$userId',
													dated 			= now()
													
													Where delivery_id='".$recipt_id."'";
													//draft			= '1'echo $update; exit;
			  mysql_query($update);
			  $_SESSION['DRAFT']['delivery_id'] = $recipt_id;
	 } else{
   			  $insQry ="insert into ".tbl_delivery." set cash_amount = '$cash_amounts',
			  									recp_first_name			= '$first_name',
			 									recp_last_name 			= '$last_name',
												recp_email 				= '$email',
												gender   			= '$gender',
												age      			= '$age',
												location 			= '$location',
												occassionid 			= '$ocassions',
												userId				= '$userId',
												dated 				= now()";
												//echo $insQry; exit;
  			$Qry = mysql_query($insQry)or die (mysql_error());
			$_SESSION['recipit_id']['New'] = mysql_insert_id();
		}
		
		unset($_SESSION['biz_rep_err']);
		unset($_SESSION['biz_rep']);
		header('location:'.ru.'step_2a'); exit;
		
  }
  
}


if (isset($_GET['SaveRecipits'])){ 

	$cash_amount = $_GET['cash_amount'];
	$first_name = $_GET['first_name'];
	$last_name = $_GET['last_name'];
	$email = $_GET['email'];
	$gender = $_GET['gender'];
	$age = $_GET['age'];
	$location = $_GET['location'];
	$ocassions = $_GET['ocassion'];
	$userId = $_GET['userId'];
	 if($_SESSION['DRAFT']['recipit_id']){
	 		 $update ="update  ".tbl_delivery." set cash_amount   = '$cash_amounts',
			  									recp_first_name		= '$first_name',
			 									recp_last_name 		= '$last_name',
												recp_email 			= '$email',
												gender   		= '$gender',
												age     	    = '$age',
												location 		= '$location',
												occassionid 		= '$ocassions',
												userId			= '$userId',
												dated 			= now(),
												draft			= '0'
												Where delivery_id='".$_SESSION['DRAFT']['recipit_id']."'";
												//echo $update; exit;
				mysql_query($update);
			  $_SESSION['recipit_id']['New'] = $_SESSION['DRAFT']['recipit_id'];
			  unset($_SESSION['DRAFT']['recipit_id']);
	 } else{
	$insQry ="insert into ".tbl_delivery." set cash_amount   = '$cash_amount',
										recp_first_name			= '$first_name',
										recp_last_name 			= '$last_name',
										recp_email 				= '$email',
										gender   			= '$gender',
										age      			= '$age',
										location 			= '$location',
										occassionid 			= '$ocassions',
										userId				= '$userId',
										dated 				= now()";
										//echo $insQry; exit;
  		$Qry = mysql_query($insQry)or die (mysql_error());
		$_SESSION['recipit_id']['New'] = mysql_insert_id();
		if($Qry)
		{
			echo 'success';
		}
	}	
}

////////////////////////////////////////////////////Save Draft /////////////////////////////////
if (isset($_POST['SaveDraft'])){ 
     	unset($_SESSION['biz_rep_err']);
	    unset($_SESSION['biz_rep']);
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['biz_rep'][$k]=$v;
	}
  	$flgs = false;


	///////////////////////name validation////////	
	
	/*if($cash_amount=='' && $first_name=='' && $email=='' && $gender=='Select' && $age=='' && $ocassion=='Event' && $ocassion=='Other' ) {
		
		$_SESSION['biz_rep_err']['errors'] = 'There are too many empty fields. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
		
	}*/
	
	
	$cash_amounts = str_replace('$','',$cash_amount);
	/*if($cash_amounts==''){
		$_SESSION['biz_rep_err']['cash_amount'] = 'You did not enter a cash gift amount. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	} else if(!is_numeric($cash_amounts)) {
		$_SESSION['biz_rep_err']['cash_amount'] = 'Please enter Numeric value. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	}
	
	if($first_name==''){
		$_SESSION['biz_rep_err']['first_name'] = 'Please enter First name. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}*/
	
	/*if($email==''){
		$_SESSION['biz_rep_err']['email'] = $_ERR['register']['email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}else*/if($email!=''){
			
			if (vpemail($email )){
			
				$_SESSION['biz_rep_err']['email'] = $_ERR['register']['emailg'].'<br/> Please correct the field <span>in red.</span>';
				header('location:'.ru.'step_1'); exit;
			
			}else {
				list($username,$domain)=explode('@',$email);
				if(checkdnsrr($domain,'MX')) {
					
				}else{
					$_SESSION['biz_rep_err']['email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
					header('location:'.ru.'step_1'); exit;
				}
			}	
	}	
	
	/*if($gender=='Select'){
		$_SESSION['biz_rep_err']['gender'] = 'You did not select gender. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}	
	
	if($age==''){
		$_SESSION['biz_rep_err']['age'] = 'Please enter age. <br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'step_1'); exit;
	
	}	*/
	
	/*if($ocassion=='Event' && $ocassion1 == ''){
			$_SESSION['biz_rep_err']['ocassion'] = 'You did not select/enter ocassion. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'step_1'); exit;	
	} 
	if($ocassion=='other' && $ocassion1 == ''){
			$_SESSION['biz_rep_err']['ocassion1'] = 'You did not select/enter ocassion. <br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'step_1'); exit;	
	}*/
	if($ocassion == 'other'){
		$ocassions =  'other_'.$ocassion1;
	}
	else if($ocassion != 'Event'){
		$ocassions = $ocassion;
	}
  if($flgs)
  {
	
		header('location:'.ru.'step_1'); exit;
		
  }else{
	 if(isset($recipt_id)){
	 		 $update ="update  ".tbl_delivery." set cash_amount   = '$cash_amounts',
			  									recp_first_name		= '$first_name',
			 									recp_last_name 		= '$last_name',
												recp_email 			= '$email',
												gender   		= '$gender',
												age     	    = '$age',
												location 		= '$location',
												occassionid 		= '$ocassions',
												userId			= '$userId',
												dated 			= now(),
												draft			= '1',
												step			= 'step_1' 
												Where delivery_id='".$recipt_id."'";
												//echo $update; exit;
											  mysql_query($update);
							$_SESSION['DRAFT']['delivery_id'] = $recipt_id;
	 } else{ $insQry1 ="insert into ".tbl_delivery." set cash_amount   = '$cash_amounts',
			  									recp_first_name		= '$first_name',
			 									recp_last_name 		= '$last_name',
												recp_email 			= '$email',
												gender   		= '$gender',
												age     	    = '$age',
												location 		= '$location',
												occassionid 		= '$ocassions',
												userId			= '$userId',
												dated 			= now(),
												draft			= '1',
												step			= 'step_1' ";
											//echo $insQry1; exit;
											//echo $repId=$rs_ques['recipit_id'];exit;
  		$Qry = mysql_query($insQry1)or die (mysql_error());
		}
		unset($_SESSION['biz_rep_err']);
		unset($_SESSION['biz_rep']);
		unset($_SESSION['COPYINFO']);
		header('location:'.ru.'dashboard'); exit;
		
  }
  
}
/*if (isset($_GET['SaveDraft'])){ 

	$cash_amount = $_GET['cash_amount'];
	$first_name = $_GET['first_name'];
	$last_name = $_GET['last_name'];
	$email = $_GET['email'];
	$gender = $_GET['gender'];
	$age = $_GET['age'];
	$location = $_GET['location'];
	$ocassion = $_GET['ocassion'];
	$userId = $_GET['userId'];
	
	$insQry1 ="insert into gift_draft set cash_gift   = '$cash_amounts',
									first_name		= '$first_name',
									last_name 		= '$last_name',
									email 			= '$email',
									gender   		= '$gender',
									age     	    = '$age',
									location 		= '$location',
									ocassion 		= '$ocassions',
									userId			= '$userId',
									dated 			= now(),
									step			= 'step_1' ";
									//echo $insQry1; exit;
  		$Qry = mysql_query($insQry1)or die (mysql_error());
		$_SESSION['recipit_id']['New'] = mysql_insert_id();
		if($Qry)
		{
			echo 'success';
		}
}*/
?>