<?php
//include_once("../config/config.php");
	function get_product_name($pid){
		$result=mysql_query("select pro_name from ".tbl_product." where proid=$pid") or die("select pro_name from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['pro_name'];
	}
	function get_prices($pid){
		$result=mysql_query("select price from ".tbl_product." where proid=$pid") or die("select pro_name from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['price'];
	}
	function get_pro_image($pid){
		$result=mysql_query("select image_code from ".tbl_product." where proid=$pid") or die("select pro_name from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['image_code'];
	}
	function remove_product($pid){
		$pid=intval($pid);
		$max=count($_SESSION['shop_cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['shop_cart'][$i]['productid']){
		//echo $_SESSION['cart'][$i];exit;
				unset($_SESSION['shop_cart'][$i]);
				break;
			}
		}
		$_SESSION['shop_cart']=array_values($_SESSION['shop_cart']);
	}
	function get_order_total(){
		$max=count($_SESSION['shop_cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['shop_cart'][$i]['productid'];
			$q=$_SESSION['shop_cart'][$i]['qty'];
			$price=get_prices($pid);
			$sum+=$price*$q;
		}
		return $sum;
	}
	function addtocart($pid,$q){
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['shop_cart'])){
			if(product_exists($pid)) return;
			$max=count($_SESSION['shop_cart']);
			$_SESSION['shop_cart'][$max]['productid']=$pid;
			$_SESSION['shop_cart'][$max]['qty']=$q;
		}
		else{
			$_SESSION['shop_cart']=array();
			$_SESSION['shop_cart'][0]['productid']=$pid;
			$_SESSION['shop_cart'][0]['qty']=$q;
		}
	}
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['shop_cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['shop_cart'][$i]['productid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}

?>