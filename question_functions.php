<?php
//include_once("../config/config.php");
	function remove_product($qid){
		$qid=intval($qid);
		$max=count($_SESSION['question_ans']);
		for($i=0;$i<$max;$i++){
			if($qid==$_SESSION['question_ans'][$i]['qid']){
		//echo $_SESSION['cart'][$i];exit;
				unset($_SESSION['question_ans'][$i]);
				break;
			}
		}
		$_SESSION['question_ans']=array_values($_SESSION['question_ans']);
	}
	
	function addtocart($qid,$q){
		if($qid<1 or $q<1) return;
		
		if(is_array($_SESSION['question_ans'])){
			if(product_exists($qid)) return;
			$max=count($_SESSION['question_ans']);
			$_SESSION['question_ans'][$max]['qid']=$qid;
			$_SESSION['question_ans'][$max]['qty']=$q;
			
		}
		else{
			$_SESSION['question_ans']=array();
			$_SESSION['question_ans'][0]['qid']=$qid;
			$_SESSION['question_ans'][0]['qty']=$q;
		}
	}
	function product_exists($qid){
		$qid=intval($qid);
		$max=count($_SESSION['question_ans']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($qid==$_SESSION['question_ans'][$i]['qid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}

?>